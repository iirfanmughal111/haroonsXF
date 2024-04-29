<?php


namespace Snog\Movies\ThreadType;


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
		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		if ($thread->Movie) {
			return ['Snog\Movies:Forum\ViewTypeMovie', 'snog_movies_thread_view_type_movie'];
		}

		return parent::getThreadViewAndTemplate($thread);
	}

	public function isFirstPostPinned(Thread $thread): bool
	{
		return true;
	}

	public function getThreadViewTemplateOverrides(Thread $thread, array $extra = []): array
	{
		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		$movie = $thread->Movie;
		if ($movie) {
			$overrides = [
				'pinned_first_post_macro' => 'snog_movies_post_macros::movie',
				'pinned_first_post_macro_args' => []
			];

			$options = \XF::options();
			$threadInfo = $options->tmdbthreads_showThreadInfo;

			if (isset($threadInfo['production_companies'])) {
				/** @var \Snog\Movies\Repository\Company $companyRepo */
				$companyRepo = \XF::repository('Snog\Movies:Company');

				$companyFinder = $companyRepo->findCompaniesForList()
					->where('company_id', $movie->tmdb_production_company_ids);

				$companies = $companyFinder->fetch($options->tmdbthreads_companiesLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'companies' => $companies,
				];
			}

			if (isset($threadInfo['cast_tab'])) {
				/** @var \Snog\Movies\Repository\Cast $castRepo */
				$castRepo = \XF::repository('Snog\Movies:Cast');
				$castFinder = $castRepo->findCastForMovie($movie->tmdb_id);

				$castLimit = $options->tmdbthreads_castsLimit;
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
				/** @var \Snog\Movies\Repository\Crew $crewRepo */
				$crewRepo = \XF::repository('Snog\Movies:Crew');
				$crewFinder = $crewRepo->findCrewForMovie($movie->tmdb_id);

				$crewLimit = $options->tmdbthreads_crewLimit;
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
				/** @var \Snog\Movies\Repository\Video $videoRepo */
				$videoRepo = \XF::repository('Snog\Movies:Video');

				$videoLimit = $options->tmdbthreads_videoLimit;
				$videoFinder = $videoRepo->findVideosForList()
					->forMovie($movie->tmdb_id);
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
	 * @param \Snog\Movies\XF\Entity\Thread|Thread $thread
	 * @param Post $firstDisplayedPost
	 * @param int $page
	 * @param array $extraData
	 * @return array|null
	 */
	public function getLdStructuredData(Thread $thread, Post $firstDisplayedPost, int $page, array $extraData = [])
	{
		$data = parent::getLdStructuredData($thread, $firstDisplayedPost, $page, $extraData);

		$movie = $thread->Movie;
		if (!$movie) {
			return $data;
		}

		$data['@type'] = 'Movie';
		$data['name'] = $movie->tmdb_title;
		$data['director'] = $movie->tmdb_director;
		$data['dateCreated'] = $movie->tmdb_release;
		$data['duration'] = "PT{$movie->tmdb_runtime}M";
		$data['image'] = $movie->getImageUrl('l');
		$data['description'] = $movie->tmdb_plot;

		if ($movie->tmdb_rating > 0) {
			$data['aggregateRating'] = [
				'@type' => 'AggregateRating',
				'bestRating' => 5,
				'worstRating' => 1,
				'ratingCount' => $movie->tmdb_votes,
				'ratingValue' => $movie->tmdb_rating
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

		return \XF::app()->templater()->renderTemplate('public:snog_movies_thread_type_fields_movie', $params);
	}

	public function setupMessagePreparer(Thread $thread, Post $post, \XF\Service\Message\Preparer $preparer)
	{
		if (!$post->isFirstPost()) {
			return;
		}

		$preparer->setConstraint('allowEmpty', true);
	}

	/**
	 * @param \Snog\Movies\XF\Service\Thread\Creator $creator
	 * @return void
	 */
	public function onMovieThreadCreate(\XF\Service\Thread\Creator $creator)
	{
		$thread = $creator->getThread();

		$apiResponse = $thread->getOption('movieApiResponse');
		if (!$apiResponse) {
			return;
		}

		/** @var \Snog\Movies\Service\Thread\TypeData\MovieCreator $typeCreator */
		$typeCreator = $creator->getTypeDataSaver();
		$movieCreator = $typeCreator->getMovieCreator();

		$movie = $movieCreator->getMovie();

		$comment = $creator->getPost()->message;

		$options = \XF::options();

		// CREATE DEFAULT THREAD/MESSAGE WITHOUT PRETTY FORMATTING

		$message = $movie->getPostMessage();
		if (!$options->tmdbthreads_force_comments) {
			$message .= $comment;
		}

		if (\XF::options()->tmdbthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
		} else {
			$title = $thread->title;
		}

		$creator->setContent($title, $message);

		$forum = $creator->getForum();
		if (!$options->tmdbthreads_force_comments && $forum->canUploadAndManageAttachments()) {
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


		/** @var \Snog\Movies\Service\Thread\TypeData\MovieCreator $typeCreator */
		$typeCreator = \XF::service('Snog\Movies:Thread\TypeData\MovieCreator', $thread);

		$movieCreator = $typeCreator->getMovieCreator();

		$movieId = $request->filter('snog_movies_tmdb_id', 'str');
		if (stristr($movieId, 'themoviedb.org/tv/')) {
			$thread->error(\XF::phrase('snog_movies_error_tv_id'));
		}

		/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
		$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');
		$movieCreator->setMovieId($tmdbHelper->parseMovieId($movieId));

		$thread->setOption('movieApiResponse', $movieCreator->getMovieApiResponse());

		return $typeCreator;
	}

	public function onThreadDelete(Thread $thread)
	{
		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		$movie = $thread->Movie;
		if ($movie) {
			$movie->delete();
		}
	}

	public function onThreadLeaveType(Thread $thread, array $typeData, bool $isDelete)
	{
		if (!$isDelete) {
			/** @var \Snog\Movies\XF\Entity\Thread $thread */
			$movie = $thread->Movie;
			if ($movie) {
				$movie->delete();
				$thread->adjustMovieThreadCount(-1);
			}
		}
	}
}
