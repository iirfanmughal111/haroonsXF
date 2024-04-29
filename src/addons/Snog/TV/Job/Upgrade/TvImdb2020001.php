<?php

namespace Snog\TV\Job\Upgrade;

class TvImdb2020001 extends \XF\Job\AbstractRebuildJob
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

			$externalIds = $tmdbClient->getTv($tv->tv_id)->getExternalIds();
			if (isset($externalIds['imdb_id']))
			{
				$tv->fastUpdate('imdb_id', $externalIds['imdb_id']);
			}
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('update');
	}
}