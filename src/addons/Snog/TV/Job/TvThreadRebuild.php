<?php

namespace Snog\TV\Job;

class TvThreadRebuild extends \XF\Job\AbstractRebuildJob
{
	protected $defaultData = [
		'tvIds' => null,
		'rebuildTypes' => []
	];

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		if ($this->data['tvIds'] !== null)
		{
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM xf_snog_tv_thread
				WHERE thread_id > ? 
				  AND tv_episode = 0
				  AND tv_season = 0 
				  AND tv_id IN ({$db->quote($this->data['tvIds'])})
				ORDER BY thread_id
			", $batch
			), $start);
		}
		else
		{
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM xf_snog_tv_thread
				WHERE thread_id > ? AND tv_episode = 0 AND tv_season = 0
				ORDER BY thread_id
			", $batch
			), $start);
		}
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->app->em()->find('Snog\TV:TV', $id, ['Thread', 'Thread.FirstPost']);
		if ($tv)
		{
			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$options = $this->app->options();

			/** @var \Snog\TV\Repository\TV $tvRepo */
			$tvRepo = $this->app->repository('Snog\TV:TV');

			try
			{
				$subRequests = $tvRepo->getSubRequestForFullApiRequest('rebuild');
				$tvData = $tmdbClient->getTv($tv->tv_id)->getDetails($subRequests);
			}
			catch (\Exception $exception)
			{
				return;
			}

			if ($tmdbClient->hasError())
			{
				return;
			}

			if (!$tvData)
			{
				return;
			}

			$tv->setFromApiResponse($tvData);

			$post = $tv->Thread->FirstPost;
			if ($post)
			{
				$post->message = $tv->getPostMessage();
				$post->saveIfChanged($saved, true, false);
			}

			$tv->saveIfChanged($saved, true, false);

			$editor = $this->setupTvEdit($tv, $tvData);
			if (!$editor->validate($errors))
			{
				return;
			}

			$editor->save();
			$this->finalizeTvEdit($editor, $tvData);
		}
	}

	protected function setupTvEdit(\Snog\TV\Entity\TV $tv, array $tvData)
	{
		/** @var \Snog\TV\Service\TV\Editor $editor */
		$editor = $this->app->service('Snog\TV:TV\Editor', $tv);
		$editor->setFromApiResponse($tvData);

		$postEditor = $editor->getPostEditor();
		if ($postEditor)
		{
			$postEditor->setMessage($tv->getPostMessage());
		}

		return $editor;
	}

	protected function finalizeTvEdit(\Snog\TV\Service\TV\Editor $editor, $tvData)
	{
		$tv = $editor->getTv();

		$options = $this->app->options();
		if ($options->TvThreads_useLocalImages)
		{
			/** @var \Snog\TV\Service\TV\Image $imageService */
			$imageService = $this->app->service('Snog\TV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->TvThreads_largePosterSize);
			$imageService->updateImage();
		}

		$backdropSize = $this->app->options()->TvThreads_backdropCoverSize;
		if (\XF::isAddOnActive('ThemeHouse/Covers') && $backdropSize != 'none' && isset($tvData['backdrop_path']))
		{
			/** @var \Snog\TV\Service\Thread\Cover $coverService */
			$coverService = $this->app->service('Snog\TV:Thread\Cover', $tv);
			$coverService->setIsAutomated(true);
			$coverService->update($tvData['backdrop_path']);
		}

		/** @var \Snog\TV\Repository\TV $tvRepo */
		$tvRepo = $this->app->repository('Snog\TV:TV');

		if (in_array('credits', $this->data['rebuildTypes']) && $options->TvThreads_fetchCredits)
		{
			$creditsData = [];
			if (isset($tvData['aggregate_credits']))
			{
				$creditsData = $tvData['aggregate_credits'];
			}
			elseif (isset($tvData['credits']))
			{
				$creditsData = $tvData['credits'];
			}

			if ($creditsData)
			{
				$tvRepo->insertOrUpdateShowCredits($tv->tv_id, 0, 0, $creditsData);
			}
		}

		if (in_array('videos', $this->data['rebuildTypes']) && $options->TvThreads_fetchVideos && isset($tvData['videos']['results']))
		{
			$tvRepo->insertOrUpdateShowVideos($tv->tv_id, 0, 0, $tvData['videos']['results'], true);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_general_info');
	}


}