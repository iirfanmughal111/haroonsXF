<?php

namespace nick97\TraktMovies\Job;

class MovieChanges extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'total' => 0,
		'count' => 0,
		'movieIds' => [],
		'nextPage' => 1,
		'totalPages' => 1
	];

	public function run($maxRunTime)
	{
		$changesTracking = $this->app->options()->traktthreads_trackChanges;
		if (!$changesTracking)
		{
			return $this->complete();
		}

		$nextPage = $this->data['nextPage'];

		$movieIds = $this->data['movieIds'];

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		try
		{
			$response = $traktClient->getChanges()->getMoviesChangeList($nextPage, [
				'start_date' => date(
					'Y-m-d',
					\XF::$time - $this->app->options()->traktthreads_trackChangesPeriod * 86400
				)
			]);
		}
		catch (\Exception $exception)
		{
			\XF::logException($exception);
			return $this->resumeLater();
		}

		if (!$response)
		{
			return $this->complete();
		}

		if (!empty($response['results']))
		{
			$newMovieIds = array_column($response['results'], 'id');
			$this->data['movieIds'] = array_merge($movieIds, $newMovieIds);
		}

		$this->data['totalPages'] = $response['total_pages'];

		if ($nextPage > $response['total_pages'])
		{
			if (!empty($this->data['movieIds']))
			{
				$db = $this->app->db();

				$existingTvIds = $db->fetchAll(
					'SELECT trakt_id FROM nick97_trakt_movies_thread WHERE trakt_id IN (' . $db->quote($this->data['movieIds']) . ')'
				);

				if ($existingTvIds)
				{
					$this->app->jobManager()->enqueueUnique(
						'traktMoviesRebuildChanges',
						'nick97\TraktMovies:MovieRebuild',
						[
							'movieIds' => $existingTvIds,
							'rebuildCredits' => true,
							'rebuildVideos' => true
						],
						false
					);
				}
			}

			return $this->complete();
		}

		$this->data['nextPage'] = $nextPage + 1;

		return $this->resume();
	}

	protected function resumeLater()
	{
		$resume = $this->resume();
		$resume->continueDate = \XF::$time + 600;

		return $resume;
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('trakt_movies_fetch_changes');
		return sprintf('%s... (%d/%d)', $actionPhrase, $this->data['nextPage'], $this->data['totalPages']);
	}

	public function canCancel()
	{
		return true;
	}

	public function canTriggerByChoice()
	{
		return true;
	}
}