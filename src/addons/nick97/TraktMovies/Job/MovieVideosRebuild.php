<?php

namespace nick97\TraktMovies\Job;

class MovieVideosRebuild extends \XF\Job\AbstractRebuildJob
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
			/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$apiResponse = $traktClient->getMovie($movie->trakt_id)->getVideos();
			if ($traktClient->hasError())
			{
				return;
			}

			$db = $this->app->db();
			$db->beginTransaction();

			$this->processMovie($movie, $apiResponse);

			$db->commit();
		}
	}

	protected function processMovie(\nick97\TraktMovies\Entity\Movie $movie, $apiResponse)
	{
		/** @var \nick97\TraktMovies\Repository\Movie $movieRepo */
		$movieRepo = $this->app->repository('nick97\TraktMovies:Movie');
		$movieRepo->deleteAllVideosForMovie($movie->trakt_id);

		$results = $apiResponse['results'] ?? [];
		if (!$results)
		{
			return;
		}

		$movieRepo->insertOrUpdateMovieVideos($movie->trakt_id, $results);
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_videos');
	}
}