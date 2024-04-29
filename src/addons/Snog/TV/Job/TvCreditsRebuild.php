<?php

namespace Snog\TV\Job;

class TvCreditsRebuild extends \XF\Job\AbstractRebuildJob
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

		$apiShow = $tmdbClient->getTv($tv->tv_id);

		if ($tv->tv_episode)
		{
			$rawCredits = $apiShow->getSeason($tv->tv_season)->getEpisode($tv->tv_episode)->getCredits();
		}
		elseif ($tv->tv_season)
		{
			$rawCredits = $apiShow->getSeason($tv->tv_season)->getCredits();
		}
		else if ($this->app->options()->TvThreads_aggregateCredits)
		{
			$rawCredits = $apiShow->getAggregateCredits();
		}
		else
		{
			$rawCredits = $apiShow->getCredits();
		}

		if ($tmdbClient->hasError())
		{
			return;
		}
		$casts = $rawCredits['cast'] ?? [];
		$crews = $rawCredits['crew'] ?? [];

		$ungroupedCredits = array_merge($casts, $crews);

		$this->app->jobManager()->enqueueUnique('snog_tv_' . $tv->tv_id, 'Snog\TV:TvNewPersons', [
			'newPersons' => $ungroupedCredits
		]);

		/** @var \Snog\TV\Repository\TV $tvRepo */
		$tvRepo = $this->app->repository('Snog\TV:TV');
		$tvRepo->insertOrUpdateShowCredits($tv->tv_id, $tv->tv_season, $tv->tv_episode, $rawCredits);
	}

	protected function getStatusType()
	{
		// TODO: Implement getStatusType() method.
	}
}