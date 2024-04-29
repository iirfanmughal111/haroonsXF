<?php

namespace Snog\TV\Job;

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
				FROM xf_snog_tv_thread
				WHERE thread_id > ? AND tv_episode = 0 AND tv_season = 0
					AND tv_image != ''
				ORDER BY thread_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->app->em()->find('Snog\TV:TV', $id);
		if ($tv)
		{
			/** @var \Snog\TV\Service\TV\Image $imageService */
			$imageService = $this->app->service('Snog\TV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->TvThreads_largePosterSize);
			if (!$imageService->updateImage())
			{
				$tv->tv_image = '';
			}

			$tv->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_tv');
	}


}