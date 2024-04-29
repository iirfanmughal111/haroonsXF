<?php

namespace Snog\Movies\Job;

class MovieRebuild extends \XF\Job\AbstractRebuildJob
{
	protected $defaultData = [
		'movieIds' => null,
		'rebuildCredits' => false,
		'rebuildVideos' => false
	];

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		if ($this->data['movieIds'] !== null)
		{
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM xf_snog_movies_thread
				WHERE thread_id > ? 
				  AND tmdb_id IN ({$db->quote($this->data['movieIds'])})
				ORDER BY thread_id
			", $batch
			), $start);
		}
		else
		{
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM xf_snog_movies_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
			), $start);
		}
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\Movies\Entity\Movie $movie */
		$movie = $this->app->em()->find('Snog\Movies:Movie', $id, ['Thread', 'Thread.FirstPost']);
		if ($movie)
		{
			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			try
			{
				$movieData = $tmdbClient->getMovie($movie->tmdb_id)->getDetails(['videos', 'trailers', 'casts']);
			}
			catch (\Exception $exception)
			{
				return;
			}

			if ($tmdbClient->hasError())
			{
				return;
			}

			if (!$movieData)
			{
				return;
			}

			$editor = $this->setupMovieEdit($movie, $movieData);
			if (!$editor->validate($errors))
			{
				return;
			}

			$editor->save();
			$this->finalizeTvEdit($editor, $movieData);
		}
	}

	protected function setupMovieEdit(\Snog\Movies\Entity\Movie $movie, array $tvData)
	{
		/** @var \Snog\Movies\Service\Movie\Editor $editor */
		$editor = $this->app->service('Snog\Movies:Movie\Editor', $movie);
		$editor->setFromApiResponse($tvData);

		$postEditor = $editor->getPostEditor();
		if ($postEditor)
		{
			$postEditor->setMessage($movie->getPostMessage());
		}

		return $editor;
	}

	protected function finalizeTvEdit(\Snog\Movies\Service\Movie\Editor $editor, $apiResponse)
	{
		$movie = $editor->getMovie();

		/** @var \Snog\Movies\Service\Movie\Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Movie\Image', $movie);
		$imageService->setImageFromApiPath($movie->tmdb_image, $this->app->options()->tmdbthreads_largePosterSize);
		$imageService->updateImage();

		$backdropSize = $this->app->options()->tmdbthreads_backdropCoverSize;
		if (\XF::isAddOnActive('ThemeHouse/Covers') && $backdropSize != 'none' && isset($apiResponse['backdrop_path']))
		{
			/** @var \Snog\Movies\Service\Thread\Cover $coverService */
			$coverService = $this->app->service('Snog\Movies:Thread\Cover', $movie);
			$coverService->setIsAutomated(true);
			$coverService->update($apiResponse['backdrop_path']);
		}

		$options = $this->app->options();

		/** @var \Snog\Movies\Repository\Movie $movieRepo */
		$movieRepo = $this->app->repository('Snog\Movies:Movie');

		if ($this->data['rebuildCredits'] && $options->tmdbthreads_fetchCredits && isset($apiResponse['casts']))
		{
			$movieRepo->insertOrUpdateMovieCredits($movie->tmdb_id, $apiResponse['casts']);
		}

		if ($this->data['rebuildVideos'] && $options->tmdbthreads_fetchVideos && isset($apiResponse['videos']['results']))
		{
			$movieRepo->insertOrUpdateMovieVideos($movie->tmdb_id, $apiResponse['videos']['results']);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_rebuild_general_movie_info');
	}
}