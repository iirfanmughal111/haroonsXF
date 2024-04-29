<?php

namespace nick97\TraktMovies\Job;

class MovieCreditsRebuild extends \XF\Job\AbstractRebuildJob
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

			$creditsResponse = $traktClient->getMovie($movie->trakt_id)->getCredits();
			if ($traktClient->getError())
			{
				return;
			}

			$casts = $creditsResponse['cast'] ?? [];
			$crews = $creditsResponse['crew'] ?? [];

			$ungroupedCredits = array_merge($casts, $crews);

			if ($ungroupedCredits)
			{
				$this->app->jobManager()->enqueueUnique('trakt_movie_' . $movie->trakt_id, 'nick97\TraktMovies:MovieNewPersons', [
					'newPersons' => $ungroupedCredits
				]);
			}

			/** @var \nick97\TraktMovies\Repository\Movie $movieRepo */
			$movieRepo = $this->app->repository('nick97\TraktMovies:Movie');
			$movieRepo->insertOrUpdateMovieCredits($movie->trakt_id, $creditsResponse);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_credits');
	}
}