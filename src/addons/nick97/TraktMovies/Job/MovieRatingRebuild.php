<?php


namespace nick97\TraktMovies\Job;


class MovieRatingRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM nick97_trakt_movies_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktMovies\Entity\Movie $movie */
		$movie = $this->app->em()->find('nick97\TraktMovies:Movie', $id);
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
		return \XF::phrase('trakt_movies_recalculate_ratings');
	}
}