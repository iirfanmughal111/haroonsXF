<?php


namespace Snog\Movies\Job;


class MovieTmdbRatingRebuild extends \XF\Job\AbstractRebuildJob
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
			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$movieInfo = $tmdbClient->getMovie($movie->tmdb_id)->getDetails();
			if ($tmdbClient->hasError())
			{
				return;
			}

			if (isset($movieInfo['popularity']))
			{
				$movie->tmdb_popularity = $movieInfo['popularity'];
			}

			$movie->saveIfChanged();
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