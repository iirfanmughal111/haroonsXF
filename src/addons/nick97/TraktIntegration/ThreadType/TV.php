<?php

namespace nick97\TraktIntegration\ThreadType;


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
		/** @var \Snog\TV\XF\Entity\Thread $thread */
		if ($thread->TV)
		{
			return ['Snog\TV:Forum\ViewTypeTv', 'snog_tv_thread_view_type_tv'];
		}

		return parent::getThreadViewAndTemplate($thread);
	}

	public function isFirstPostPinned(Thread $thread): bool
	{
		return true;
	}

	public function getThreadViewTemplateOverrides(Thread $thread, array $extra = []): array
	{
		/** @var \Snog\TV\XF\Entity\Thread $thread */
		$tv = $thread->TV;
		if ($tv)
		{
			$overrides = [
				'pinned_first_post_macro' => 'snog_tv_post_macros::tv',
				'pinned_first_post_macro_args' => [],
				'post_macro' => 'snog_tv_post_macros::post',
			];

			$options = \XF::options();
			$threadInfo = $options->TvThreads_showThread;

			if (isset($threadInfo['production_companies']))
			{
				/** @var \Snog\TV\Repository\Company $companyRepo */
				$companyRepo = \XF::repository('Snog\TV:Company');

				$companyFinder = $companyRepo->findCompaniesForList()
					->where('company_id', $tv->tmdb_production_company_ids);

				$companies = $companyFinder->fetch($options->TvThreads_companiesLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'companies' => $companies,
				];
			}

			if (isset($threadInfo['networks']))
			{
				/** @var \Snog\TV\Repository\Network $networkRepo */
				$networkRepo = \XF::repository('Snog\TV:Network');

				$networkFinder = $networkRepo->findNetworksForList()
					->where('network_id', $tv->tmdb_network_ids);

				$networks = $networkFinder->fetch($options->TvThreads_networksLimit);

				$overrides['pinned_first_post_macro_args'] += [
					'networks' => $networks,
				];
			}

			if (isset($threadInfo['cast_tab']))
			{
				/** @var \Snog\TV\Repository\Cast $castRepo */
				$castRepo = \XF::repository('Snog\TV:Cast');
				$castFinder = $castRepo->findCastForTv($tv);
				$castFinder->useDefaultOrder();

				$castLimit = $options->TvThreads_castsLimit;
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

			if (isset($threadInfo['crew_tab']))
			{
				/** @var \Snog\TV\Repository\Crew $crewRepo */
				$crewRepo = \XF::repository('Snog\TV:Crew');
				$crewFinder = $crewRepo->findCrewForTv($tv);
				$crewFinder->useDefaultOrder();

				$crewLimit = $options->TvThreads_creditsLimit;
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

			if (isset($threadInfo['videos_tab']))
			{
				/** @var \Snog\TV\Repository\Video $videoRepo */
				$videoRepo = \XF::repository('Snog\TV:Video');
				$videoFinder = $videoRepo->findVideosForList()
					->forShow($tv->tv_id)
					->forSeason($tv->tv_season)
					->forEpisode($tv->tv_episode);

				$videosLimit = $options->TvThreads_videosLimit;
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
	 * @param \Snog\TV\XF\Entity\Thread|Thread $thread
	 * @param Post $firstDisplayedPost
	 * @param int $page
	 * @param array $extraData
	 * @return array|null
	 */
	public function getLdStructuredData(Thread $thread, Post $firstDisplayedPost, int $page, array $extraData = [])
	{
		$data = parent::getLdStructuredData($thread, $firstDisplayedPost, $page, $extraData);

		$TV = $thread->TV;
		if (!$TV)
		{
			return $data;
		}

		$data['@type'] = 'Movie';
		$data['name'] = $TV->tv_title;
		$data['director'] = $TV->tv_director;
		$data['dateCreated'] = $TV->tv_release;
		$data['image'] = $TV->getImageUrl('l');
		$data['description'] = $TV->tv_plot;

		if ($TV->tv_rating > 0)
		{
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

		return \XF::app()->templater()->renderTemplate('public:nick97_trakt_tv_thread_type_fields', $params);
	}

	public function addTypeDataToApiResult(Thread $thread, \XF\Api\Result\EntityResult $result, int $verbosity = \XF\Mvc\Entity\Entity::VERBOSITY_NORMAL, array $options = [])
	{
		if (empty($options['skip_tv']))
		{
			$result->includeRelation('TV');
		}
	}

	public function setupMessagePreparer(Thread $thread, Post $post, \XF\Service\Message\Preparer $preparer)
	{
		if (!$post->isFirstPost())
		{
			return;
		}

		$preparer->setConstraint('allowEmpty', true);
	}

	/**
	 * @param \Snog\TV\XF\Service\Thread\Creator $creator
	 * @return void
	 */
	public function onTvThreadCreate(\XF\Service\Thread\Creator $creator)
	{
		$thread = $creator->getThread();

		$tvData = $thread->getOption('tvData');
		if (!$tvData)
		{
			return;
		}

		/** @var \Snog\TV\Service\Thread\TypeData\TvCreator $typeCreator */
		$typeCreator = $creator->getTypeDataSaver();
		$tvCreator = $typeCreator->getTvCreator();

		$tv = $tvCreator->getTv();

		$comment = $creator->getPost()->message;

		$options = \XF::options();

		// CREATE DEFAULT THREAD/MESSAGE WITHOUT PRETTY FORMATTING

		if ($options->TvThreads_syncTitle)
		{
			$title = $tv->getExpectedThreadTitle();
		}
		else
		{
			$title = $thread->title;
		}

		$creator->setContent($title, $tv->getPostMessage());

		$forum = $creator->getForum();
		if (!$options->TvThreads_force_comments && $forum->canUploadAndManageAttachments())
		{
			// Unassociate attachments from this post
			$creator->setAttachmentHash(null);
		}

		$thread->setOption('tvOriginalMessage', $comment);
	}

	public function processExtraDataService(Thread $thread, string $context, Request $request, array $options = [])
	{
		if (!$thread->isInsert())
		{
			return null;
		}

		/** @var \Snog\TV\Service\Thread\TypeData\TvCreator $typeCreator */
		$typeCreator = \XF::service('Snog\TV:Thread\TypeData\TvCreator', $thread);
		$tvCreator = $typeCreator->getTvCreator();

		$tvId = $request->filter('snog_tv_tv_id', 'str');
		if (stristr($tvId, 'themoviedb.org/movie/'))
		{
			$thread->error(\XF::phrase('snog_tv_error_movie_id'));
		}

		if (stristr($tvId, 'themoviedb.org/search'))
		{
			$thread->error(\XF::phrase('snog_tv_error_id_not_valid'));
		}

		/** @var \Snog\TV\Helper\Tmdb\Show $tmdbHelper */
		$tmdbHelper = \XF::helper('Snog\TV:Tmdb\Show');
		$tvCreator->setTvId($tmdbHelper->parseShowId($tvId));

		$thread->setOption('tvData', $tvCreator->getTvData());

		return $typeCreator;
	}

	public function onThreadDelete(Thread $thread)
	{
		/** @var \Snog\TV\XF\Entity\Thread $thread */
		$tv = $thread->TV;
		if ($tv)
		{
			$tv->delete();
		}
	}

	public function onThreadLeaveType(Thread $thread, array $typeData, bool $isDelete)
	{
		if (!$isDelete)
		{
			/** @var \Snog\TV\XF\Entity\Thread $thread */
			$tv = $thread->TV;
			if ($tv)
			{
				$tv->delete();
				$thread->adjustTvThreadCount(-1);
			}
		}
	}
}
