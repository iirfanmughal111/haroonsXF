<?php

namespace Snog\Movies\Job;

class MovieCommunityChangesApply extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		$changesTracking = $this->app->options()->tmdbthreads_trackCommunityChanges;
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
				FROM xf_snog_movies_thread
				WHERE thread_id > ? AND tmdb_last_change_date < ?
				ORDER BY thread_id
			", $batch
		), [$start, \XF::$time - 86400 * $this->app->options()->tmdbthreads_trackChangesPeriod]);
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

			try
			{
				$apiResponse = $tmdbClient->getMovie($movie->tmdb_id)->getChanges([
					'start_date' => date(
						'Y-m-d',
						\XF::$time + $this->app->options()->tmdbthreads_trackChangesPeriod * 86400
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

			$changesTracking = $this->app->options()->tmdbthreads_trackCommunityChanges;

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
				$movie->tmdb_last_change_date = \XF::$time;
			}

			$movie->saveIfChanged($movieChanges, true, false);

			$db->commit();
		}
	}

	protected function applyInternalChanges(\Snog\Movies\Entity\Movie $movie, $changes)
	{
	}

	protected function filterItemsLanguage(array $items)
	{
		return array_filter($items, function ($item) {
			$langCode = $this->app->options()->tmdbthreads_changesLanguage;
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

	protected function _applyChangeImages(\Snog\Movies\Entity\Movie $movie, $changes)
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
				$movie->tmdb_image = $lastImage['value']['poster']['file_path'];
			}
		}

		/** @var \Snog\Movies\Service\Movie\Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Movie\Image', $movie);
		$imageService->setImageFromApiPath($movie->tmdb_image);
		$imageService->updateImage();
	}

	protected function _applyChangeName(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);

			if (isset($lastItem['value']))
			{
				$movie->tmdb_title = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeStatus(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$lastItem = end($items);
			if (isset($lastItem['value']))
			{
				$movie->tmdb_status = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeRuntime(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$lastItem = end($items);
			if (isset($lastItem['value']))
			{
				$movie->tmdb_runtime = $lastItem['value'];
			}
		}
	}
	
	protected function _applyChangeImdbId(\Snog\Movies\Entity\Movie $movie, $changes)
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

	protected function applyChangeCast(\Snog\Movies\Entity\Movie $movie, $changes)
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
				/** @var \Snog\Movies\Entity\Cast $entity */
				$entity = $this->app->em()->create('Snog\Movies:Cast');

				$entity->bulkSet([
					'tmdb_id' => $movie->tmdb_id,
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
				$db->insertBulk('xf_snog_movies_cast', $characterChanges, true);
			}

			if ($deletedPersonIds)
			{
				$db->delete('xf_snog_movies_cast', 'person_id = IN(?) AND tmdb_id = ?', [
					$db->quote($deletedPersonIds),
					$movie->tmdb_id
				]);
			}
		}
	}

	protected function _applyChangeOverview(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);
			if ($lastItem && !empty($lastItem['value']))
			{
				$movie->tmdb_plot = $lastItem['value'];
			}
		}
	}

	protected function _applyChangeVideos(\Snog\Movies\Entity\Movie $movie, $changes)
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
					'tmdb_id' => $movie->tmdb_id,
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
				$db->insertBulk('xf_snog_movies_video', $videoData, true);
			}

			if ($deletedVideoIds)
			{
				$db->delete('xf_snog_movies_video', 'video_id = IN(?)', $db->quote($deletedVideoIds));
			}
		}
	}

	protected function _applyChangeReleaseDates(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
			$items = $this->filterItemsLanguage($items);
			$lastItem = end($items);
			if (isset($lastItem['value']['release_date']))
			{
				$movie->tmdb_release = $lastItem['value']['release_date'];
			}
		}
	}

	protected function _applyChangeCharacterNames(\Snog\Movies\Entity\Movie $movie, $changes)
	{
		$items = $changes['items'] ?? [];
		if ($items)
		{
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_movies_apply_changes');
	}
}