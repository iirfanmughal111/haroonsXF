<?php

namespace nick97\TraktTV\Job;

class TvForumImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected $defaultData = [
		'tvIds' => null
	];

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT node_id
				FROM nick97_trakt_tv_forum
				WHERE node_id > ?
					AND tv_image != ''
				ORDER BY node_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TVForum $TVForum */
		$TVForum = $this->app->em()->find('nick97\TraktTV:TVForum', $id);
		if ($TVForum) {
			/** @var \nick97\TraktTV\Service\TVForum\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:TVForum\Image', $TVForum);
			$imageService->setImageFromApiPath($TVForum->tv_image, $this->app->options()->traktTvThreads_largePosterSize);
			if (!$imageService->updateImage()) {
				$TVForum->tv_image = '';
			}

			$TVForum->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_tv_forums');
	}
}
