<?php

namespace Snog\Movies\Job;

class MovieCreditsRebuild extends \XF\Job\AbstractRebuildJob
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

			$creditsResponse = $tmdbClient->getMovie($movie->tmdb_id)->getCredits();
			if ($tmdbClient->getError())
			{
				return;
			}

			$casts = $creditsResponse['cast'] ?? [];
			$crews = $creditsResponse['crew'] ?? [];

			$ungroupedCredits = array_merge($casts, $crews);

			if ($ungroupedCredits)
			{
				$this->app->jobManager()->enqueueUnique('snog_movie_' . $movie->tmdb_id, 'Snog\Movies:MovieNewPersons', [
					'newPersons' => $ungroupedCredits
				]);
			}

			/** @var \Snog\Movies\Repository\Movie $movieRepo */
			$movieRepo = $this->app->repository('Snog\Movies:Movie');
			$movieRepo->insertOrUpdateMovieCredits($movie->tmdb_id, $creditsResponse);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_rebuild_credits');
	}
}