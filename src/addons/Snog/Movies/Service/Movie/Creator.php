<?php


namespace Snog\Movies\Service\Movie;


class Creator extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \XF\Entity\Thread
	 */
	protected $thread;

	protected $apiResponse;

	protected $movieData;

	/**
	 * @var \Snog\Movies\Entity\Movie
	 */
	protected $movie;

	/**
	 * @var Preparer
	 */
	protected $moviePreparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread)
	{
		parent::__construct($app);
		$this->setThread($thread);
		$this->moviePreparer = $this->service('Snog\Movies:Movie\Preparer', $this->movie);
	}

	protected function setThread(\XF\Entity\Thread $thread)
	{
		$this->thread = $thread;

		/** @var \Snog\Movies\Entity\Movie $movie */
		$movie = $this->em()->create('Snog\Movies:Movie');
		$threadId = $thread->thread_id;
		if (!$threadId) {
			$threadId = $movie->em()->getDeferredValue(function () use ($thread) {
				return $thread->thread_id;
			}, 'save');
		}

		$movie->thread_id = $threadId;

		$this->apiResponse = $this->thread->getOption('movieApiResponse');

		$this->movie = $movie;
	}

	public function getThread()
	{
		return $this->thread;
	}

	public function getMovie()
	{
		return $this->movie;
	}

	public function getMovieApiResponse()
	{
		return $this->apiResponse;
	}

	public function setMovieId(int $movieId)
	{
		if (!$movieId) {
			return;
		}

		$this->movie->tmdb_id = $movieId;

		if (!$this->apiResponse) {
			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getMovie($movieId)->getDetails(['casts', 'trailers', 'videos', 'watch/providers']);


			if ($tmdbClient->hasError()) {
				$this->movie->error($tmdbClient->getError());
				return;
			}

			$this->apiResponse = $apiResponse;
			$this->setMovieData($apiResponse);
		}
	}

	public function setComment($comment)
	{
		$this->movie->comment = $comment;
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool) $perform;
	}

	public function setIsAutomated()
	{
		$this->setPerformValidations(false);
	}

	public function setMovieData(array $data)
	{
		$this->movie->setFromApiResponse($data);
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$movie = $this->movie;
		$movie->preSave();
		$errors = $movie->getErrors();

		if ($this->performValidations) {
			if (!empty($movie->tmdb_release)) {
				$releaseExploded = explode('-', $movie->tmdb_release);
				if (!isset($releaseExploded[0]) || strlen($releaseExploded[0]) !== 4) {
					$errors[] = \XF::phrase('snog_movies_error_release_date_format');
				}
			}
		}

		return $errors;
	}

	protected function getBlockingJobs()
	{
		$options = $this->app->options();

		$jobList = [];

		$apiResponse = $this->apiResponse;

		if ($options->tmdbthreads_fetchCredits) {
			if (isset($apiResponse['casts'])) {
				$casts = $apiResponse['casts']['cast'] ?? [];
				$crews = $apiResponse['casts']['crew'] ?? [];

				$ungroupedCredits = array_merge($casts, $crews);

				$jobList[] = [
					'Snog\Movies:MovieNewPersons', [
						'newPersons' => $ungroupedCredits
					]
				];
			}
		}

		if ($options->tmdbthreads_fetchCompanies && !empty($apiResponse['production_companies'])) {
			$jobList[] = [
				'Snog\Movies:MovieNewCompanies', [
					'companyIds' => array_column($apiResponse['production_companies'], 'id')
				]
			];
		}

		return $jobList;
	}

	protected function runBlockingJobs()
	{
		$jobList = $this->getBlockingJobs();

		if ($jobList) {
			$this->app->jobManager()->enqueueAutoBlocking('XF:Atomic', [
				'execute' => $jobList
			]);
		}
	}

	protected function getFinalJobs()
	{
		$jobList = [];

		$movie = $this->movie;
		$apiResponse = $this->apiResponse;
		$options = $this->app->options();

		if ($options->tmdbthreads_use_genres && $options->tmdbthreads_crosslink) {
			$jobList[] = [
				'Snog\Movies:CrossLinkCreate',
				[
					'check_genres' => explode(',', $movie->tmdb_genres),
					'original_thread_id' => $this->thread->thread_id,
					'movieApiResponse' => $apiResponse
				]
			];
		}

		return $jobList;
	}

	protected function runFinalJobs()
	{
		$movie = $this->movie;

		$jobList = $this->getFinalJobs();

		if ($jobList) {
			$this->app->jobManager()->enqueueUnique('snogMoviesInsert' . $movie->thread_id, 'XF:Atomic', [
				'execute' => $jobList
			], false);
		}
	}

	protected function _save()
	{
		$app = $this->app;
		$options = $app->options();

		$db = $this->db();
		$db->beginTransaction();

		$movie = $this->movie;
		$thread = $movie->Thread;

		$movie->save(true, false);

		$this->moviePreparer->afterInsert();

		/** @var Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Movie\Image', $movie);
		$imageService->setImageFromApiPath($movie->tmdb_image);
		$imageService->updateImage();

		if (isset($thread->User) && $thread->User->user_id != \XF::visitor()->user_id) {
			$app->logger()->logModeratorAction('thread', $thread, 'snog_movies_movie_create');
		}

		$apiResponse = $this->apiResponse;

		if ($options->tmdbthreads_force_comments) {
			$comment = $thread->getOption('movieOriginalMessage');
			if ($comment) {
				/** @var \XF\Service\Thread\Replier $replier */
				$replier = \XF::service('XF:Thread\Replier', $thread);
				$replier->setMessage($comment);
				if ($thread->Forum->canUploadAndManageAttachments()) {
					$replier->setAttachmentHash(\XF::app()->request()->filter('attachment_hash', 'str'));
				}
				$replier->save();
			}
		}

		$this->runBlockingJobs();



		/** @var \Snog\Movies\Repository\Movie $movieRepo */
		$movieRepo = $this->repository('Snog\Movies:Movie');

		if ($options->tmdbthreads_fetchCredits && isset($apiResponse['casts'])) {
			$movieRepo->insertOrUpdateMovieCredits($movie->tmdb_id, $apiResponse['casts']);
		}

		if ($options->tmdbthreads_fetchVideos && isset($apiResponse['videos']['results'])) {
			$movieRepo->insertOrUpdateMovieVideos($movie->tmdb_id, $apiResponse['videos']['results']);
		}

		$db->commit();

		$this->runFinalJobs();

		return $movie;
	}
}
