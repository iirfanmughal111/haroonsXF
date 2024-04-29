<?php

namespace nick97\TraktTV\Job\Upgrade;

class TvFirstAirDate2020008 extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ?
					AND first_air_date = 0
				ORDER BY thread_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id);
		if ($tv) {
			$tv->fastUpdate('first_air_date', strtotime($tv->tv_release));
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('rebuild');
	}
}
