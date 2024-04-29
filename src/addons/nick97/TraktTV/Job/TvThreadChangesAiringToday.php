<?php

namespace nick97\TraktTV\Job;

class TvThreadChangesAiringToday extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'total' => 0,
		'count' => 0,
		'tvIds' => [],
		'nextPage' => 1,
		'totalPages' => 1
	];

	public function run($maxRunTime)
	{
		$changesTracking = $this->app->options()->traktTvThreads_trackChangesAiringToday;
		if (!$changesTracking) {
			return $this->complete();
		}

		$nextPage = $this->data['nextPage'];

		$tvIds = $this->data['tvIds'];

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		try {
			$response = $traktClient->getAiringToday()->getList($nextPage);
		} catch (\Exception $exception) {
			return $this->resumeLater();
		}

		if (!$response || empty($response['results'])) {
			return $this->complete();
		}

		$this->data['totalPages'] = $response['total_pages'];

		if (!empty($response['results'])) {
			$newTvIds = array_column($response['results'], 'id');
			$this->data['tvIds'] = array_merge($tvIds, $newTvIds);
		}

		if ($nextPage >= $response['total_pages']) {
			if (!empty($this->data['tvIds'])) {
				$db = $this->app->db();

				$existingTvIds = $db->fetchAll(
					'SELECT tv_id FROM nick97_trakt_tv_thread WHERE tv_id IN (' . $db->quote($this->data['tvIds']) . ')'
				);

				if ($existingTvIds) {
					$this->app->jobManager()->enqueueUnique(
						'tvThreadRebuildChanges',
						'nick97\TraktTV:TvThreadRebuild',
						[
							'tvIds' => $existingTvIds,
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
		$actionPhrase = \XF::phrase('trakt_tv_fetch_changes');
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
