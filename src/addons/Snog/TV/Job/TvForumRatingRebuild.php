<?php

namespace Snog\TV\Job;

class TvForumRatingRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT node_id
				FROM xf_snog_tv_forum
				WHERE node_id > ?
				ORDER BY node_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TVForum $tvForum */
		$tvForum = $this->app->em()->find('Snog\TV:TVForum', $id);
		if ($tvForum)
		{
			$tvForum->rebuildRating(true);
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_recalculate_forum_ratings');
	}
}