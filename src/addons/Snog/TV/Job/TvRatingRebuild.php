<?php

namespace Snog\TV\Job;

class TvRatingRebuild extends \XF\Job\AbstractRebuildJob
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
			$tv->rebuildRating(true);
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_recalculate_ratings');
	}
}