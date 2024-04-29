<?php

namespace Snog\TV\Job;

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
				FROM xf_snog_tv_forum
				WHERE node_id > ?
					AND tv_image != ''
				ORDER BY node_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TVForum $TVForum */
		$TVForum = $this->app->em()->find('Snog\TV:TVForum', $id);
		if ($TVForum)
		{
			/** @var \Snog\TV\Service\TVForum\Image $imageService */
			$imageService = $this->app->service('Snog\TV:TVForum\Image', $TVForum);
			$imageService->setImageFromApiPath($TVForum->tv_image, $this->app->options()->TvThreads_largePosterSize);
			if (!$imageService->updateImage())
			{
				$TVForum->tv_image = '';
			}

			$TVForum->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_tv_forums');
	}


}