<?php

namespace Snog\TV\Job;

class TvWatchProviderRebuild extends \XF\Job\AbstractRebuildJob
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
		if ($tv)
		{
			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getTv($tv->tv_id)->getWatchProviders();
			if ($tmdbClient->hasError())
			{
				return;
			}

			$tv->tmdb_watch_providers = $apiResponse['results'] ?? [];
			$tv->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_watch_providers');
	}
}