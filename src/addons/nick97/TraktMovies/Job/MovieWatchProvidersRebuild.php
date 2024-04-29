<?php

namespace nick97\TraktMovies\Job;

class MovieWatchProvidersRebuild extends \XF\Job\AbstractRebuildJob
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

			$apiResponse = $traktClient->getMovie($movie->trakt_id)->getWatchProviders();
			if ($traktClient->hasError())
			{
				return;
			}

			$movie->trakt_watch_providers = $apiResponse['results'] ?? [];
			$movie->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_watch_providers');
	}
}