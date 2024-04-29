<?php

namespace nick97\TraktTV\Job;

class TvThreadImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected $defaultData = [
		'tvIds' => null
	];

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ? AND tv_episode = 0 AND tv_season = 0
					AND tv_image != ''
				ORDER BY thread_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id);
		if ($tv) {
			/** @var \nick97\TraktTV\Service\TV\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->traktTvThreads_largePosterSize);
			if (!$imageService->updateImage()) {
				$tv->tv_image = '';
			}

			$tv->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_tv');
	}
}
