<?php

namespace nick97\TraktTV\ThreadType;


use XF\Entity\Post;
use XF\Entity\Thread;
use XF\Http\Request;

class TV extends \XF\ThreadType\AbstractHandler
{

	/**
	 * @inheritDoc
	 */
	public function getTypeIconClass(): string
	{
		return 'fa-tv-retro';
	}

	public function getThreadViewAndTemplate(Thread $thread): array
	{
		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		if ($thread->traktTV) {
			return ['nick97\TraktTV:Forum\ViewTypeTv', 'trakt_tv_thread_view_type_tv'];
		}

		return parent::getThreadViewAndTemplate($thread);
	}

	public function isFirstPostPinned(Thread $thread): bool
	{
		return true;
	}

	public function getThreadViewTemplateOverrides(Thread $thread, array $extra = []): array
	{
		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$tv = $thread->traktTV;
		if ($tv) {
			$overrides = [
				'pinned_first_post_macro' => 'trakt_tv_post_macros::tv',
				'pinned_first_post_macro_args' => [],
				'post_macro' => 'trakt_tv_post_macros::post',
			];

			$options = \XF::options();
			$threadInfo = $options->traktTvThreads_showThread;

			if (isset($threadInfo['production_companies'])) {
				/** @var \nick97\TraktTV\Repository\Company $companyRepo */
				$companyRepo = \XF::repository('nick97\TraktTV:Company');

				$companyFinder = $companyRepo->findCompaniesForList()
					->where('company_id', $tv->trakt_production_company_ids);

				$companies = $companyFinder->fetch($options->traktTvThreads_companiesLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'companies' => $companies,
				];
			}

			if (isset($threadInfo['networks'])) {
				/** @var \nick97\TraktTV\Repository\Network $networkRepo */
				$networkRepo = \XF::repository('nick97\TraktTV:Network');

				$networkFinder = $networkRepo->findNetworksForList()
					->where('network_id', $tv->trakt_network_ids);

				$networks = $networkFinder->fetch($options->traktTvThreads_networksLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'networks' => $networks,
				];
			}

			if (isset($threadInfo['cast_tab'])) {
				/** @var \nick97\TraktTV\Repository\Cast $castRepo */
				$castRepo = \XF::repository('nick97\TraktTV:Cast');
				$castFinder = $castRepo->findCastForTv($tv);
				$castFinder->useDefaultOrder();

				$castLimit = $options->traktTvThreads_castsLimit;
				$castFinder->limit($castLimit);
				$castsTotal = $castFinder->total();
				$castsHasMore = $castsTotal > $castLimit;

				$casts = $castFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'casts' => $casts,
					'castsTotal' => $castsTotal,
					'castsHasMore' => $castsHasMore
				];
			}

			if (isset($threadInfo['crew_tab'])) {
				/** @var \nick97\TraktTV\Repository\Crew $crewRepo */
				$crewRepo = \XF::repository('nick97\TraktTV:Crew');
				$crewFinder = $crewRepo->findCrewForTv($tv);
				$crewFinder->useDefaultOrder();

				$crewLimit = $options->traktTvThreads_creditsLimit;
				$crewFinder->limit($crewLimit);
				$crewTotal = $crewFinder->total();
				$crewsHasMore = $crewTotal > $crewLimit;

				$crews = $crewFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'crews' => $crews,
					'crewsTotal' => $crewTotal,
					'crewsHasMore' => $crewsHasMore,
				];
			}

			if (isset($threadInfo['videos_tab'])) {
				/** @var \nick97\TraktTV\Repository\Video $videoRepo */
				$videoRepo = \XF::repository('nick97\TraktTV:Video');
				$videoFinder = $videoRepo->findVideosForList()
					->forShow($tv->tv_id)
					->forSeason($tv->tv_season)
					->forEpisode($tv->tv_episode);

				$videosLimit = $options->traktTvThreads_videosLimit;
				$videoFinder->limit($videosLimit);
				$videosTotal = $videoFinder->total();
				$videosHasMore = $videosTotal > $videosLimit;

				$videos = $videoFinder->fetch();

				$overrides['pinned_first_post_macro_args'] += [
					'videos' => $videos,
					'videosTotal' => $videosTotal,
					'videosHasMore' => $videosHasMore
				];
			}

			return $overrides;
		}

		return parent::getThreadViewTemplateOverrides($thread, $extra);
	}

	protected function getLdSnippet($message, int $length = null)
	{
		return \XF::app()->stringFormatter()->snippetString($message, 0, ['stripBbCode' => true]);
	}

	/**
	 * @param \nick97\TraktTV\XF\Entity\Thread|Thread $thread
	 * @param Post $firstDisplayedPost
	 * @param int $page
	 * @param array $extraData
	 * @return array|null
	 */
	public function getLdStructuredData(Thread $thread, Post $firstDisplayedPost, int $page, array $extraData = [])
	{
		$data = parent::getLdStructuredData($thread, $firstDisplayedPost, $page, $extraData);

		$TV = $thread->traktTV;
		if (!$TV) {
			return $data;
		}

		$data['@type'] = 'Movie';
		$data['name'] = $TV->tv_title;
		$data['director'] = $TV->tv_director;
		$data['dateCreated'] = $TV->tv_release;
		$data['image'] = $TV->getImageUrl('l');
		$data['description'] = $TV->tv_plot;

		if ($TV->tv_rating > 0) {
			$data['aggregateRating'] = [
				'@type' => 'AggregateRating',
				'bestRating' => 5,
				'worstRating' => 1,
				'ratingCount' => $TV->tv_votes,
				'ratingValue' => $TV->tv_rating
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

		return \XF::app()->templater()->renderTemplate('public:trakt_tv_thread_type_fields_tv', $params);
	}

	public function addTypeDataToApiResult(Thread $thread, \XF\Api\Result\EntityResult $result, int $verbosity = \XF\Mvc\Entity\Entity::VERBOSITY_NORMAL, array $options = [])
	{
		if (empty($options['skip_tv'])) {
			$result->includeRelation('TV');
		}
	}

	public function setupMessagePreparer(Thread $thread, Post $post, \XF\Service\Message\Preparer $preparer)
	{
		if (!$post->isFirstPost()) {
			return;
		}

		$preparer->setConstraint('allowEmpty', true);
	}

	/**
	 * @param \nick97\TraktTV\XF\Service\Thread\Creator $creator
	 * @return void
	 */
	public function onTvThreadCreate(\XF\Service\Thread\Creator $creator)
	{
		$thread = $creator->getThread();

		$tvData = $thread->getOption('tvData');
		if (!$tvData) {
			return;
		}

		/** @var \nick97\TraktTV\Service\Thread\TypeData\TvCreator $typeCreator */
		$typeCreator = $creator->getTypeDataSaver();
		$tvCreator = $typeCreator->getTvCreator();

		$tv = $tvCreator->getTv();

		$comment = $creator->getPost()->message;

		$options = \XF::options();

		// CREATE DEFAULT THREAD/MESSAGE WITHOUT PRETTY FORMATTING

		if ($options->traktTvThreads_syncTitle) {
			$title = $tv->getExpectedThreadTitle();
		} else {
			$title = $thread->title;
		}

		$creator->setContent($title, $tv->getPostMessage());

		$forum = $creator->getForum();
		if (!$options->traktTvThreads_force_comments && $forum->canUploadAndManageAttachments()) {
			// Unassociate attachments from this post
			$creator->setAttachmentHash(null);
		}

		$thread->setOption('tvOriginalMessage', $comment);
	}

	public function processExtraDataService(Thread $thread, string $context, Request $request, array $options = [])
	{
		if (!$thread->isInsert()) {
			return null;
		}

		/** @var \nick97\TraktTV\Service\Thread\TypeData\TvCreator $typeCreator */
		$typeCreator = \XF::service('nick97\TraktTV:Thread\TypeData\TvCreator', $thread);
		$tvCreator = $typeCreator->getTvCreator();

		$tvId = $request->filter('trakt_tv_tv_id', 'str');
		if (stristr($tvId, 'themoviedb.org/movie/')) {
			$thread->error(\XF::phrase('trakt_tv_error_movie_id'));
		}

		if (stristr($tvId, 'themoviedb.org/search')) {
			$thread->error(\XF::phrase('trakt_tv_error_id_not_valid'));
		}

		// $clientKey = \XF::options()->traktTvThreads_clientkey;

		// 	if (!$clientKey) {
		// 		throw $this->exception(
		// 			$this->notFound(\XF::phrase("nick97_tv_trakt_api_key_not_found"))
		// 		);
		// 	}

		/** @var \nick97\TraktTV\Helper\Trakt\Show $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktTV:Trakt\Show');
		$tvCreator->setTvId($traktHelper->parseShowId($tvId));

		$thread->setOption('tvData', $tvCreator->getTvData());

		return $typeCreator;
	}

	public function onThreadDelete(Thread $thread)
	{
		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$tv = $thread->traktTV;
		if ($tv) {
			$tv->delete();
		}
	}

	public function onThreadLeaveType(Thread $thread, array $typeData, bool $isDelete)
	{
		if (!$isDelete) {
			/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
			$tv = $thread->traktTV;
			if ($tv) {
				$tv->delete();
				$thread->adjustTvThreadCount(-1);
			}
		}
	}
}
