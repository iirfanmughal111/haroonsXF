<?php

namespace Snog\TV\Job;

class TvPostImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT post_id
				FROM xf_snog_tv_post
				WHERE post_id > ? AND tv_image != ''
				ORDER BY post_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TVPost $episode */
		$episode = $this->app->em()->find('Snog\TV:TVPost', $id);
		if ($episode && $episode->tv_image)
		{
			/** @var \Snog\TV\Service\TVPost\Image $imageService */
			$imageService = $this->app->service('Snog\TV:TVPost\Image', $episode);
			$imageService->setImageFromApiPath($episode->tv_image, 'w300');

			$imageService->updateImage();

			$episode->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_episodes');
	}
}