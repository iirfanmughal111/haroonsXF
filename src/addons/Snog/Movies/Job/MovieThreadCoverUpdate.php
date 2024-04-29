<?php

namespace Snog\Movies\Job;

class MovieThreadCoverUpdate extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers'))
		{
			return $this->complete();
		}

		$backdropSize = $this->app->options()->tmdbthreads_backdropCoverSize;
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
		if (!$movie)
		{
			return;
		}

		/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$movieData = $tmdbClient->getMovie($movie->tmdb_id)->getDetails();
		if (empty($movieData['backdrop_path']) || $movie->backdrop_path == $movieData['backdrop_path'])
		{
			return;
		}

		/** @var \Snog\Movies\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('Snog\Movies:Thread\Cover', $movie);
		$coverService->update($movieData['backdrop_path']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_rebuild_thread_covers');
	}
}