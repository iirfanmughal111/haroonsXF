<?php

namespace nick97\TraktMovies\Job;

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
				FROM nick97_trakt_movies_thread
				WHERE thread_id > ? 
				  AND trakt_id IN ({$db->quote($this->data['movieIds'])})
				ORDER BY thread_id
			", $batch
			), $start);
		}
		else
		{
			return $db->fetchAllColumn($db->limit(
				"
				SELECT thread_id
				FROM nick97_trakt_movies_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
			), $start);
		}
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktMovies\Entity\Movie $movie */
		$movie = $this->app->em()->find('nick97\TraktMovies:Movie', $id, ['Thread', 'Thread.FirstPost']);
		if ($movie)
		{
			/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			try
			{
				$movieData = $traktClient->getMovie($movie->trakt_id)->getDetails(['videos', 'trailers', 'casts']);
			}
			catch (\Exception $exception)
			{
				return;
			}

			if ($traktClient->hasError())
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

	protected function setupMovieEdit(\nick97\TraktMovies\Entity\Movie $movie, array $tvData)
	{
		/** @var \nick97\TraktMovies\Service\Movie\Editor $editor */
		$editor = $this->app->service('nick97\TraktMovies:Movie\Editor', $movie);
		$editor->setFromApiResponse($tvData);

		$postEditor = $editor->getPostEditor();
		if ($postEditor)
		{
			$postEditor->setMessage($movie->getPostMessage());
		}

		return $editor;
	}

	protected function finalizeTvEdit(\nick97\TraktMovies\Service\Movie\Editor $editor, $apiResponse)
	{
		$movie = $editor->getMovie();

		/** @var \nick97\TraktMovies\Service\Movie\Image $imageService */
		$imageService = $this->app->service('nick97\TraktMovies:Movie\Image', $movie);
		$imageService->setImageFromApiPath($movie->trakt_image, $this->app->options()->traktthreads_largePosterSize);
		$imageService->updateImage();

		$backdropSize = $this->app->options()->traktthreads_backdropCoverSize;
		if (\XF::isAddOnActive('ThemeHouse/Covers') && $backdropSize != 'none' && isset($apiResponse['backdrop_path']))
		{
			/** @var \nick97\TraktMovies\Service\Thread\Cover $coverService */
			$coverService = $this->app->service('nick97\TraktMovies:Thread\Cover', $movie);
			$coverService->setIsAutomated(true);
			$coverService->update($apiResponse['backdrop_path']);
		}

		$options = $this->app->options();

		/** @var \nick97\TraktMovies\Repository\Movie $movieRepo */
		$movieRepo = $this->app->repository('nick97\TraktMovies:Movie');

		if ($this->data['rebuildCredits'] && $options->traktthreads_fetchCredits && isset($apiResponse['casts']))
		{
			$movieRepo->insertOrUpdateMovieCredits($movie->trakt_id, $apiResponse['casts']);
		}

		if ($this->data['rebuildVideos'] && $options->traktthreads_fetchVideos && isset($apiResponse['videos']['results']))
		{
			$movieRepo->insertOrUpdateMovieVideos($movie->trakt_id, $apiResponse['videos']['results']);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_rebuild_general_movie_info');
	}
}