<?php

namespace Snog\Movies\Job;

class MovieVideosRebuild extends \XF\Job\AbstractRebuildJob
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

			$apiResponse = $tmdbClient->getMovie($movie->tmdb_id)->getVideos();
			if ($tmdbClient->hasError())
			{
				return;
			}

			$db = $this->app->db();
			$db->beginTransaction();

			$this->processMovie($movie, $apiResponse);

			$db->commit();
		}
	}

	protected function processMovie(\Snog\Movies\Entity\Movie $movie, $apiResponse)
	{
		/** @var \Snog\Movies\Repository\Movie $movieRepo */
		$movieRepo = $this->app->repository('Snog\Movies:Movie');
		$movieRepo->deleteAllVideosForMovie($movie->tmdb_id);

		$results = $apiResponse['results'] ?? [];
		if (!$results)
		{
			return;
		}

		$movieRepo->insertOrUpdateMovieVideos($movie->tmdb_id, $results);
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_rebuild_videos');
	}
}