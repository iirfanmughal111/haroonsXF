<?php

namespace nick97\TraktMovies\Job;

class MovieThreadCoverUpdate extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers'))
		{
			return $this->complete();
		}

		$backdropSize = $this->app->options()->traktthreads_backdropCoverSize;
		if ($backdropSize == 'none')
		{
			return $this->complete();
		}

		return parent::run($maxRunTime);
	}

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
		if (!$movie)
		{
			return;
		}

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$movieData = $traktClient->getMovie($movie->trakt_id)->getDetails();
		if (empty($movieData['backdrop_path']) || $movie->backdrop_path == $movieData['backdrop_path'])
		{
			return;
		}

		/** @var \nick97\TraktMovies\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('nick97\TraktMovies:Thread\Cover', $movie);
		$coverService->update($movieData['backdrop_path']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_thread_covers');
	}
}