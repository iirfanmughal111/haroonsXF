<?php


namespace Snog\Movies\Job;


class MovieRatingRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM xf_snog_movies_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\Movies\Entity\Movie $movie */
		$movie = $this->app->em()->find('Snog\Movies:Movie', $id);
		if ($movie)
		{
			$movie->rebuildRating(true);
		}
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_recalculate_ratings');
	}
}