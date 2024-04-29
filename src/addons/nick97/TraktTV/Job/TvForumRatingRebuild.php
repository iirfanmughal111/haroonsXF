<?php

namespace nick97\TraktTV\Job;

class TvForumRatingRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT node_id
				FROM nick97_trakt_tv_forum
				WHERE node_id > ?
				ORDER BY node_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TVForum $tvForum */
		$tvForum = $this->app->em()->find('nick97\TraktTV:TVForum', $id);
		if ($tvForum) {
			$tvForum->rebuildRating(true);
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_recalculate_forum_ratings');
	}
}
