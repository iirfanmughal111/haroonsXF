<?php

namespace nick97\TraktTV\Job;

class TvThreadRebuild extends \XF\Job\AbstractRebuildJob
{
	protected $defaultData = [
		'tvIds' => null,
		'rebuildTypes' => []
	];

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		if ($this->data['tvIds'] !== null) {
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ? 
				  AND tv_episode = 0
				  AND tv_season = 0 
				  AND tv_id IN ({$db->quote($this->data['tvIds'])})
				ORDER BY thread_id
			",
				$batch
			), $start);
		} else {
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ? AND tv_episode = 0 AND tv_season = 0
				ORDER BY thread_id
			",
				$batch
			), $start);
		}
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id, ['Thread', 'Thread.FirstPost']);
		if ($tv) {
			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$options = $this->app->options();

			/** @var \nick97\TraktTV\Repository\TV $tvRepo */
			$tvRepo = $this->app->repository('nick97\TraktTV:TV');

			try {
				$subRequests = $tvRepo->getSubRequestForFullApiRequest('rebuild');
				$tvData = $traktClient->getTv($tv->tv_id)->getDetails($subRequests);
			} catch (\Exception $exception) {
				return;
			}

			if ($traktClient->hasError()) {
				return;
			}

			if (!$tvData) {
				return;
			}

			$tv->setFromApiResponse($tvData);

			$post = $tv->Thread->FirstPost;
			if ($post) {
				$post->message = $tv->getPostMessage();
				$post->saveIfChanged($saved, true, false);
			}

			$tv->saveIfChanged($saved, true, false);

			$editor = $this->setupTvEdit($tv, $tvData);
			if (!$editor->validate($errors)) {
				return;
			}

			$editor->save();
			$this->finalizeTvEdit($editor, $tvData);
		}
	}

	protected function setupTvEdit(\nick97\TraktTV\Entity\TV $tv, array $tvData)
	{
		/** @var \nick97\TraktTV\Service\TV\Editor $editor */
		$editor = $this->app->service('nick97\TraktTV:TV\Editor', $tv);
		$editor->setFromApiResponse($tvData);

		$postEditor = $editor->getPostEditor();
		if ($postEditor) {
			$postEditor->setMessage($tv->getPostMessage());
		}

		return $editor;
	}

	protected function finalizeTvEdit(\nick97\TraktTV\Service\TV\Editor $editor, $tvData)
	{
		$tv = $editor->getTv();

		$options = $this->app->options();
		if ($options->traktTvThreads_useLocalImages) {
			/** @var \nick97\TraktTV\Service\TV\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->traktTvThreads_largePosterSize);
			$imageService->updateImage();
		}

		$backdropSize = $this->app->options()->traktTvThreads_backdropCoverSize;
		if (\XF::isAddOnActive('ThemeHouse/Covers') && $backdropSize != 'none' && isset($tvData['backdrop_path'])) {
			/** @var \nick97\TraktTV\Service\Thread\Cover $coverService */
			$coverService = $this->app->service('nick97\TraktTV:Thread\Cover', $tv);
			$coverService->setIsAutomated(true);
			$coverService->update($tvData['backdrop_path']);
		}

		/** @var \nick97\TraktTV\Repository\TV $tvRepo */
		$tvRepo = $this->app->repository('nick97\TraktTV:TV');

		if (in_array('credits', $this->data['rebuildTypes']) && $options->traktTvThreads_fetchCredits) {
			$creditsData = [];
			if (isset($tvData['aggregate_credits'])) {
				$creditsData = $tvData['aggregate_credits'];
			} elseif (isset($tvData['credits'])) {
				$creditsData = $tvData['credits'];
			}

			if ($creditsData) {
				$tvRepo->insertOrUpdateShowCredits($tv->tv_id, 0, 0, $creditsData);
			}
		}

		if (in_array('videos', $this->data['rebuildTypes']) && $options->traktTvThreads_fetchVideos && isset($tvData['videos']['results'])) {
			$tvRepo->insertOrUpdateShowVideos($tv->tv_id, 0, 0, $tvData['videos']['results'], true);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_general_info');
	}
}
