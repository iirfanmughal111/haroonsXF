<?php

namespace nick97\TraktTV\Job;

class TvCreditsRebuild extends \XF\Job\AbstractRebuildJob
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

		$apiShow = $traktClient->getTv($tv->tv_id);

		if ($tv->tv_episode) {
			$rawCredits = $apiShow->getSeason($tv->tv_season)->getEpisode($tv->tv_episode)->getCredits();
		} elseif ($tv->tv_season) {
			$rawCredits = $apiShow->getSeason($tv->tv_season)->getCredits();
		} else if ($this->app->options()->traktTvThreads_aggregateCredits) {
			$rawCredits = $apiShow->getAggregateCredits();
		} else {
			$rawCredits = $apiShow->getCredits();
		}

		if ($traktClient->hasError()) {
			return;
		}
		$casts = $rawCredits['cast'] ?? [];
		$crews = $rawCredits['crew'] ?? [];

		$ungroupedCredits = array_merge($casts, $crews);

		$this->app->jobManager()->enqueueUnique('trakt_tv_' . $tv->tv_id, 'nick97\TraktTV:TvNewPersons', [
			'newPersons' => $ungroupedCredits
		]);

		/** @var \nick97\TraktTV\Repository\TV $tvRepo */
		$tvRepo = $this->app->repository('nick97\TraktTV:TV');
		$tvRepo->insertOrUpdateShowCredits($tv->tv_id, $tv->tv_season, $tv->tv_episode, $rawCredits);
	}

	protected function getStatusType()
	{
		// TODO: Implement getStatusType() method.
	}
}
