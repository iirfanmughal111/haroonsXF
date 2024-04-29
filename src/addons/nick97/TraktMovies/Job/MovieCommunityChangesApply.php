<?php

namespace nick97\TraktMovies\Job;

class MovieCommunityChangesApply extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		$changesTracking = $this->app->options()->traktthreads_trackCommunityChanges;
		if (empty($changesTracking))
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
				WHERE thread_id > ? AND trakt_last_change_date < ?
				ORDER BY thread_id
			", $batch
		), [$start, \XF::$time - 86400 * $this->app->options()->traktthreads_trackChangesPeriod]);
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

			try
			{
				$apiResponse = $traktClient->getMovie($movie->trakt_id)->getChanges([
					'start_date' => date(
						'Y-m-d',
						\XF::$time + $this->app->options()->traktthreads_trackChangesPeriod * 86400
					)
				]);
			}
			catch (\Exception $exception)
			{
				return;
			}

			$changes = $apiResponse['changes'] ?? [];
			if (empty($changes))
			{
				return;
			}

			$db = $this->app->db();
			$db->beginTransaction();

			$changesTracking = $this->app->options()->traktthreads_trackCommunityChanges;

			foreach ($changes as $group)
			{
				if (isset($changesTracking[$group['key']]))
				{
					$method = '_applyChange' . \XF\Util\Php::camelCase($group['key']);
					if (method_exists($this, $method))
					{
						$this->$method($movie, $group);
					}
				}

				$this->applyInternalChanges($movie, $group);
				$movie->trakt_last_change_date = \XF::$time;
			}

			$movie->saveIfChanged($movieChanges, true, false);

			$db->commit();
		}
	}

	protected function applyInternalChanges(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
	}

	protected function filterItemsLanguage(array $items)
	{
		return array_filter($items, function ($item) {
			$langCode = $this->app->options()->traktthreads_changesLanguage;
			$langCodeParts = explode('-', $langCode);

			$lang = $langCodeParts[0] ?? '';
			$country = $langCodeParts[1] ?? '';

			$found = false;

			if ($lang != '' && isset($item['iso_639_1']) && $item['iso_639_1'] == $lang)
			{
				$found = true;
			}

			if ($country != '' && isset($item['iso_3166_1']) && $item['iso_3166_1'] != $country)
			{
				$found = true;
			}

			return $found;
		});
	}

	protected function _applyChangeImages(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);

			$newImages = array_filter($items, function ($change) {
				return in_array($change['action'], ['added', 'updated']);
			});

			$lastImage = end($newImages);
			if (!empty($lastImage['value']['poster']))
			{
				$movie->trakt_image = $lastImage['value']['poster']['file_path'];
			}
		}

		/** @var \nick97\TraktMovies\Service\Movie\Image $imageService */
		$imageService = $this->app->service('nick97\TraktMovies:Movie\Image', $movie);
		$imageService->setImageFromApiPath($movie->trakt_image);
		$imageService->updateImage();
	}

	protected function _applyChangeName(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);

			if (isset($lastItem['value']))
			{
				$movie->trakt_title = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeStatus(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$lastItem = end($items);
			if (isset($lastItem['value']))
			{
				$movie->trakt_status = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeRuntime(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$lastItem = end($items);
			if (isset($lastItem['value']))
			{
				$movie->trakt_runtime = $lastItem['value'];
			}
		}
	}
	
	protected function _applyChangeImdbId(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$lastItem = end($items);
			if (isset($lastItem['value']))
			{
				$movie->imdb_id = $lastItem['value'];
			}
		}
	}

	protected function applyChangeCast(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$castChanges = $changes['items'] ?? [];
		if ($castChanges)
		{
			$characterChanges = [];
			$deletedPersonIds = [];
			foreach ($castChanges as $change)
			{
				if ($change['action'] == 'added')
				{
					$characterChanges[] = $change['value'];
				}
				elseif ($change['action'] == 'updated')
				{
					$characterChanges[] = $change['value'];
				}
				elseif ($change['action'] == 'deleted')
				{
					$deletedPersonIds[] = $change['original_value']['person_id'];
				}
			}

			foreach ($characterChanges as $key => $change)
			{
				/** @var \nick97\TraktMovies\Entity\Cast $entity */
				$entity = $this->app->em()->create('nick97\TraktMovies:Cast');

				$entity->bulkSet([
					'trakt_id' => $movie->trakt_id,
					'person_id' => $change['person_id'],
					'character' => $change['character'],
					'credit_id' => $change['credit_id'] ?? '',
					'order' => $change['order']
				], ['forceConstraint' => true]);

				$characterChanges[$key] = $entity->toArray(false);
			}

			$db = $this->app->db();
			if ($characterChanges)
			{
				$db->insertBulk('nick97_trakt_movies_cast', $characterChanges, true);
			}

			if ($deletedPersonIds)
			{
				$db->delete('nick97_trakt_movies_cast', 'person_id = IN(?) AND trakt_id = ?', [
					$db->quote($deletedPersonIds),
					$movie->trakt_id
				]);
			}
		}
	}

	protected function _applyChangeOverview(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);
			if ($lastItem && !empty($lastItem['value']))
			{
				$movie->trakt_plot = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeVideos(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$db = $this->app->db();

		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);

			$newVideos = [];
			$deletedVideoIds = [];

			foreach ($items as $item)
			{
				if ($item['action'] == 'added')
				{
					$newVideos[] = $item['value'];
				}
				elseif ($item['action'] == 'deleted')
				{
					$deletedVideoIds[] = $item['value']['id'];
				}
			}

			$videoData = [];
			foreach ($newVideos as $video)
			{
				$videoData[] = [
					'trakt_id' => $movie->trakt_id,
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

			if ($videoData)
			{
				$db->insertBulk('nick97_trakt_movies_video', $videoData, true);
			}

			if ($deletedVideoIds)
			{
				$db->delete('nick97_trakt_movies_video', 'video_id = IN(?)', $db->quote($deletedVideoIds));
			}
		}
	}

	protected function _applyChangeReleaseDates(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);
			if (isset($lastItem['value']['release_date']))
			{
				$movie->trakt_release = $lastItem['value']['release_date'];
			}
		}
	}

	protected function _applyChangeCharacterNames(\nick97\TraktMovies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_movies_apply_changes');
	}
}