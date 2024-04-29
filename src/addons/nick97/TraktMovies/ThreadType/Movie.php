<?php


namespace nick97\TraktMovies\ThreadType;


use XF\Entity\Post;
use XF\Entity\Thread;
use XF\Http\Request;
use XF\ThreadType\AbstractHandler;

class Movie extends AbstractHandler
{
	/**
	 * @inheritDoc
	 */
	public function getTypeIconClass(): string
	{
		return 'fa-film';
	}

	public function getThreadViewAndTemplate(Thread $thread): array
	{
		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		if ($thread->traktMovie) {
			return ['nick97\TraktMovies:Forum\ViewTypeMovie', 'trakt_movies_thread_view_type_movie'];
		}

		return parent::getThreadViewAndTemplate($thread);
	}

	public function isFirstPostPinned(Thread $thread): bool
	{
		return true;
	}

	public function getThreadViewTemplateOverrides(Thread $thread, array $extra = []): array
	{
		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		$movie = $thread->traktMovie;
		if ($movie) {
			$overrides = [
				'pinned_first_post_macro' => 'trakt_movies_post_macros::movie',
				'pinned_first_post_macro_args' => []
			];

			$options = \XF::options();
			$threadInfo = $options->traktthreads_showThreadInfo;

			if (isset($threadInfo['production_companies'])) {
				/** @var \nick97\TraktMovies\Repository\Company $companyRepo */
				$companyRepo = \XF::repository('nick97\TraktMovies:Company');

				$companyFinder = $companyRepo->findCompaniesForList()
					->where('company_id', $movie->trakt_production_company_ids);

				$companies = $companyFinder->fetch($options->traktthreads_companiesLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'companies' => $companies,
				];
			}

			if (isset($threadInfo['cast_tab'])) {
				/** @var \nick97\TraktMovies\Repository\Cast $castRepo */
				$castRepo = \XF::repository('nick97\TraktMovies:Cast');
				$castFinder = $castRepo->findCastForMovie($movie->trakt_id);

				$castLimit = $options->traktthreads_castsLimit;
				$castFinder->limit($castLimit);
				$castsTotal = $castFinder->total();
				$castsHasMore = $castsTotal > $castLimit;

				$casts = $castFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'casts' => $casts,
					'castsTotal' => $castsTotal,
					'castsHasMore' => $castsHasMore,
				];
			}

			if (isset($threadInfo['crew_tab'])) {
				/** @var \nick97\TraktMovies\Repository\Crew $crewRepo */
				$crewRepo = \XF::repository('nick97\TraktMovies:Crew');
				$crewFinder = $crewRepo->findCrewForMovie($movie->trakt_id);

				$crewLimit = $options->traktthreads_crewLimit;
				$crewFinder->limit($crewLimit);
				$crewTotal = $crewFinder->total();
				$crewsHasMore = $crewTotal > $crewLimit;

				$crews = $crewFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'crews' => $crews,
					'crewTotal' => $crewTotal,
					'crewsHasMore' => $crewsHasMore,
				];
			}

			if (isset($threadInfo['videos_tab'])) {
				/** @var \nick97\TraktMovies\Repository\Video $videoRepo */
				$videoRepo = \XF::repository('nick97\TraktMovies:Video');

				$videoLimit = $options->traktthreads_videoLimit;
				$videoFinder = $videoRepo->findVideosForList()
					->forMovie($movie->trakt_id);
				$videoFinder->limit($videoLimit);
				$videosTotal = $videoFinder->total();
				$videosHasMore = $videosTotal > $videoLimit;

				$videos = $videoFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'videos' => $videos,
					'videosTotal' => $videosTotal,
					'videosHasMore' => $videosHasMore,
				];
			}

			return $overrides;
		}

		return [];
	}

	protected function getLdSnippet($message, int $length = null)
	{
		return \XF::app()->stringFormatter()->snippetString($message, 0, ['stripBbCode' => true]);
	}

	/**
	 * @param \nick97\TraktMovies\XF\Entity\Thread|Thread $thread
	 * @param Post $firstDisplayedPost
	 * @param int $page
	 * @param array $extraData
	 * @return array|null
	 */
	public function getLdStructuredData(Thread $thread, Post $firstDisplayedPost, int $page, array $extraData = [])
	{
		$data = parent::getLdStructuredData($thread, $firstDisplayedPost, $page, $extraData);

		$movie = $thread->traktMovie;
		if (!$movie) {
			return $data;
		}

		$data['@type'] = 'Movie';
		$data['name'] = $movie->trakt_title;
		$data['director'] = $movie->trakt_director;
		$data['dateCreated'] = $movie->trakt_release;
		$data['duration'] = "PT{$movie->trakt_runtime}M";
		$data['image'] = $movie->getImageUrl('l');
		$data['description'] = $movie->trakt_plot;

		if ($movie->trakt_rating > 0) {
			$data['aggregateRating'] = [
				'@type' => 'AggregateRating',
				'bestRating' => 5,
				'worstRating' => 1,
				'ratingCount' => $movie->trakt_votes,
				'ratingValue' => $movie->trakt_rating
			];
		}

		return $data;
	}

	protected function renderExtraDataEditInternal(Thread $thread, array $typeData, string $context, string $subContext, array $options = []): string
	{
		$params = [
			'thread' => $thread,
			'typeData' => $typeData,
			'context' => $context,
			'subContext' => $subContext,
			'draft' => $options['draft'] ?? []
		];

		return \XF::app()->templater()->renderTemplate('public:trakt_movies_thread_type_fields_movie', $params);
	}

	public function setupMessagePreparer(Thread $thread, Post $post, \XF\Service\Message\Preparer $preparer)
	{
		if (!$post->isFirstPost()) {
			return;
		}

		$preparer->setConstraint('allowEmpty', true);
	}

	/**
	 * @param \nick97\TraktMovies\XF\Service\Thread\Creator $creator
	 * @return void
	 */
	public function onMovieThreadCreate(\XF\Service\Thread\Creator $creator)
	{
		$thread = $creator->getThread();

		$apiResponse = $thread->getOption('movieApiResponse');
		if (!$apiResponse) {
			return;
		}

		/** @var \nick97\TraktMovies\Service\Thread\TypeData\MovieCreator $typeCreator */
		$typeCreator = $creator->getTypeDataSaver();
		$movieCreator = $typeCreator->getMovieCreator();

		$movie = $movieCreator->getMovie();

		$comment = $creator->getPost()->message;

		$options = \XF::options();

		// CREATE DEFAULT THREAD/MESSAGE WITHOUT PRETTY FORMATTING

		$message = $movie->getPostMessage();
		if (!$options->traktthreads_force_comments) {
			$message .= $comment;
		}

		if (\XF::options()->traktthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
		} else {
			$title = $thread->title;
		}

		$creator->setContent($title, $message);

		$forum = $creator->getForum();
		if (!$options->traktthreads_force_comments && $forum->canUploadAndManageAttachments()) {
			// Unassociate attachments from this post
			$creator->setAttachmentHash(null);
		}

		$thread->setOption('movieOriginalMessage', $comment);
	}

	public function processExtraDataService(Thread $thread, string $context, Request $request, array $options = [])
	{
		if (!$thread->isInsert()) {
			return null;
		}

		/** @var \nick97\TraktMovies\Service\Thread\TypeData\MovieCreator $typeCreator */
		$typeCreator = \XF::service('nick97\TraktMovies:Thread\TypeData\MovieCreator', $thread);

		$movieCreator = $typeCreator->getMovieCreator();

		$movieId = $request->filter('nick97_movies_trakt_id', 'str');
		if (!stristr($movieId, 'trakt.tv/')) {
			$thread->error(\XF::phrase('trakt_movies_error_tv_id'));
		}

		// $clientKey = \XF::options()->traktMovieThreads_apikey;

		// if (!$clientKey) {

		// 	throw $this->exception(
		// 		$this->notFound(\XF::phrase("nick97_movie_trakt_api_key_not_found"))
		// 	);
		// }

		/** @var \nick97\TraktMovies\Helper\Trakt $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktMovies:Trakt');
		$movieCreator->setMovieId($traktHelper->parseMovieId($movieId));

		$thread->setOption('movieApiResponse', $movieCreator->getMovieApiResponse());

		return $typeCreator;
	}

	public function onThreadDelete(Thread $thread)
	{
		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		$movie = $thread->traktMovie;
		if ($movie) {
			$movie->delete();
		}
	}

	public function onThreadLeaveType(Thread $thread, array $typeData, bool $isDelete)
	{
		if (!$isDelete) {
			/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
			$movie = $thread->traktMovie;
			if ($movie) {
				$movie->delete();
				$thread->adjustMovieThreadCount(-1);
			}
		}
	}
}
