<?php

namespace nick97\TraktTV\Job;

class TvPostImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT post_id
				FROM nick97_trakt_tv_post
				WHERE post_id > ? AND tv_image != ''
				ORDER BY post_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TVPost $episode */
		$episode = $this->app->em()->find('nick97\TraktTV:TVPost', $id);
		if ($episode && $episode->tv_image) {
			/** @var \nick97\TraktTV\Service\TVPost\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:TVPost\Image', $episode);
			$imageService->setImageFromApiPath($episode->tv_image, 'w300');

			$imageService->updateImage();

			$episode->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_episodes');
	}
}
