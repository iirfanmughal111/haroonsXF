<?php

namespace nick97\TraktTV\Job;

class TvVideoRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id);
		if (!$tv) {
			return;
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$apiResponse = $traktClient->getTv($tv->tv_id)->getVideos();
		if ($traktClient->hasError()) {
			return;
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$this->processShow($tv, $apiResponse);

		$db->commit();
	}

	protected function processShow(\nick97\TraktTV\Entity\TV $tv, $apiResponse)
	{
		/** @var \nick97\TraktTV\Repository\TV $tvRepo */
		$tvRepo = \XF::repository('nick97\TraktTV:TV');

		$results = $apiResponse['results'] ?? [];
		if (!$results) {
			return;
		}

		$tvRepo->insertOrUpdateShowVideos($tv->tv_id, $tv->tv_season, $tv->tv_episode, $apiResponse['results']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_videos');
	}
}
