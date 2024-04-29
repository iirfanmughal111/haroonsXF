<?php

namespace Snog\TV\Job;

class TvVideoRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM xf_snog_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->app->em()->find('Snog\TV:TV', $id);
		if (!$tv)
		{
			return;
		}

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$apiResponse = $tmdbClient->getTv($tv->tv_id)->getVideos();
		if ($tmdbClient->hasError())
		{
			return;
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$this->processShow($tv, $apiResponse);

		$db->commit();
	}

	protected function processShow(\Snog\TV\Entity\TV $tv, $apiResponse)
	{
		/** @var \Snog\TV\Repository\TV $tvRepo */
		$tvRepo = \XF::repository('Snog\TV:TV');

		$results = $apiResponse['results'] ?? [];
		if (!$results)
		{
			return;
		}

		$tvRepo->insertOrUpdateShowVideos($tv->tv_id, $tv->tv_season, $tv->tv_episode, $apiResponse['results']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_videos');
	}
}