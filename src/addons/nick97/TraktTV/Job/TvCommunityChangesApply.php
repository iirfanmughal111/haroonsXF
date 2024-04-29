<?php

namespace nick97\TraktTV\Job;

class TvCommunityChangesApply extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		$changesTracking = $this->app->options()->traktTvThreads_trackCommunityChanges;
		if (empty($changesTracking)) {
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
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ? AND trakt_last_change_date < ?
				ORDER BY thread_id
			",
			$batch
		), [$start, \XF::$time - 86400 * $this->app->options()->traktTvThreads_trackChangesPeriod]);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id);
		if ($tv) {
			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			try {
				$apiResponse = $traktClient->getTv($tv->tv_id)->getChanges([
					'start_date' => date(
						'Y-m-d',
						\XF::$time + $this->app->options()->traktTvThreads_trackChangesPeriod * 86400
					)
				]);
			} catch (\Exception $exception) {
				return;
			}

			$changes = $apiResponse['changes'] ?? [];
			if (empty($changes)) {
				return;
			}

			$db = $this->app->db();
			$db->beginTransaction();

			$changesTracking = $this->app->options()->traktTvThreads_trackCommunityChanges;

			foreach ($changes as $group) {
				if (isset($changesTracking[$group['key']])) {
					$method = '_applyChange' . \XF\Util\Php::camelCase($group['key']);
					if (method_exists($this, $method)) {
						$this->$method($tv, $group);
					}
				}

				$this->applyInternalChanges($tv, $group);
				$tv->trakt_last_change_date = \XF::$time;
			}

			$tv->saveIfChanged($tvChanges, true, false);

			$db->commit();
		}
	}

	protected function applyInternalChanges(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
	}

	protected function filterItemsLanguage(array $items)
	{
		return array_filter($items, function ($item) {
			$langCode = $this->app->options()->traktTvThreads_changesLanguage;
			$langCodeParts = explode('-', $langCode);

			$lang = $langCodeParts[0] ?? '';
			$country = $langCodeParts[1] ?? '';

			$found = false;

			if ($lang != '' && isset($item['iso_639_1']) && $item['iso_639_1'] == $lang) {
				$found = true;
			}

			if ($country != '' && isset($item['iso_3166_1']) && $item['iso_3166_1'] != $country) {
				$found = true;
			}

			return $found;
		});
	}

	protected function _applyChangeImages(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$imagesChanges = $changes['items'] ?? [];
		if ($imagesChanges) {
			$imagesChanges = $this->filterItemsLanguage($imagesChanges);

			$newImages = array_filter($imagesChanges, function ($change) {
				return in_array($change['action'], ['added', 'updated']);
			});

			$lastImage = end($newImages);
			if (!empty($lastImage['value']['poster'])) {
				$tv->tv_image = $lastImage['value']['poster']['file_path'];
			}
		}

		if ($this->app->options()->traktTvThreads_useLocalImages) {
			/** @var \nick97\TraktTV\Service\TV\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:TV\Image', $tv);
			$imageService->setImageFromApiPath($tv->tv_image, $this->app->options()->traktTvThreads_largePosterSize);
			$imageService->updateImage();
		}
	}

	protected function _applyChangeName(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$nameChanges = $changes['items'] ?? [];
		if ($nameChanges) {
			$nameChanges = $this->filterItemsLanguage($nameChanges);
			$lastName = end($nameChanges);

			if (isset($lastName['value'])) {
				$tv->tv_title = $lastName['value'];
			}
		}
	}

	protected function _applyChangeStatus(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$statusChanges = $changes['items'] ?? [];
		if ($statusChanges) {
			$lastStatus = end($statusChanges);
			if (isset($lastStatus['value'])) {
				$tv->status = $lastStatus['value'];
			}
		}
	}
	protected function _applyChangeImdbId(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$imdbChanges = $changes['items'] ?? [];
		if ($imdbChanges) {
			$lastImd = end($imdbChanges);
			if (isset($lastImd['value'])) {
				$tv->imdb_id = $lastImd['value'];
			}
		}
	}

	protected function _applyChangeEpisodeRunTime(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		// Not used
	}

	protected function _applyChangeNetwork(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		// TODO
	}

	protected function _applySeasonRegular(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$seasonRegularChanges = $changes['items'] ?? [];
		if ($seasonRegularChanges) {
			$characterChanges = [];
			$deletedPersonIds = [];
			foreach ($seasonRegularChanges as $change) {
				if ($change['action'] == 'added') {
					$characterChanges[] = $change['value'];
				} elseif ($change['action'] == 'updated') {
					$characterChanges[] = $change['value'];
				} elseif ($change['action'] == 'deleted') {
					$deletedPersonIds[] = $change['original_value']['person_id'];
				}
			}

			foreach ($characterChanges as $key => $change) {
				/** @var \nick97\TraktTV\Entity\Cast $entity */
				$entity = $this->app->em()->create('nick97\TraktTV:Cast');

				$entity->bulkSet([
					'tv_id' => $tv->tv_id,
					'person_id' => $change['person_id'],
					'character' => $change['character'],
					'credit_id' => $change['credit_id'] ?? '',
					'order' => $change['order']
				], ['forceConstraint' => true]);

				$characterChanges[$key] = $entity->toArray(false);
			}

			$db = $this->app->db();
			if ($characterChanges) {
				$db->insertBulk('nick97_trakt_tv_cast', $characterChanges, true);
			}

			if ($deletedPersonIds) {
				$db->delete('nick97_trakt_tv_cast', 'person_id = IN(?) AND tv_id = ?', [
					$db->quote($deletedPersonIds),
					$tv->tv_id
				]);
			}
		}
	}

	protected function _applyChangeOverview(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$overviewChanges = $changes['items'] ?? [];
		if ($overviewChanges) {
			$overviewChanges = $this->filterItemsLanguage($overviewChanges);
			$lastAdded = end($overviewChanges);
			if ($lastAdded && $lastAdded['value']) {
				$tv->tv_plot = $lastAdded['value'];
			}
		}
	}

	protected function _applyChangeVideos(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$db = $this->app->db();

		$videosChanges = $changes['items'] ?? [];
		if ($videosChanges) {
			$videosChanges = $this->filterItemsLanguage($videosChanges);

			$newVideos = [];
			$deletedVideoIds = [];
			foreach ($videosChanges as $videosChange) {
				if ($videosChange['action'] == 'added') {
					$newVideos[] = $videosChange['value'];
				} elseif ($videosChange['action'] == 'deleted') {
					$deletedVideoIds[] = $videosChange['value']['id'];
				}
			}

			$videoData = [];
			foreach ($newVideos as $video) {
				$videoData[] = [
					'tv_id' => $tv->tv_id,
					'tv_season' => $tv->tv_season,
					'tv_episode' => $tv->tv_episode,
					'video_id' => $video['id'] ?? '',
					'name' => $video['name'] ?? '',
					'key' => $video['key'] ?? '',
					'site' => $video['site'] ?? '',
					'size' => $video['size'] ?? 0,
					'type' => $video['type'] ?? '',
					'official' => $video['official'] ?? 0,
					'published_at' => isset($changes['time']) ? strtotime($changes['time']) : \XF::$time,
					'iso_639_1' => $changes['iso_639_1'] ?? '',
					'iso_3166_1' => $changes['iso_3166_1'] ?? '',
				];
			}

			if ($videoData) {
				$db->insertBulk('nick97_trakt_tv_video', $videoData, true);
			}

			if ($deletedVideoIds) {
				$db->delete('nick97_trakt_tv_video', 'video_id = IN(?)', $db->quote($deletedVideoIds));
			}
		}
	}

	protected function _applyChangeReleaseDates(\nick97\TraktTV\Entity\TV $tv, $changes)
	{
		$releaseDateChanges = $changes['items'] ?? [];
		if ($releaseDateChanges) {
			$releaseDateChanges = $this->filterItemsLanguage($releaseDateChanges);
			$lastChange = end($releaseDateChanges);
			if (isset($lastChange['value']['release_date'])) {
				$tv->tv_release = $lastChange['value']['release_date'];
			}
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_apply_changes');
	}
}
