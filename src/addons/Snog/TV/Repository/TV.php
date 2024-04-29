<?php

namespace Snog\TV\Repository;

class TV extends \XF\Mvc\Entity\Repository
{
	public function getSubRequestForFullApiRequest($context)
	{
		$options = $this->options();
		$subRequests = ['videos', 'external_ids'];
		if ($options->TvThreads_aggregateCredits)
		{
			$subRequests[] = 'aggregate_credits';
		}
		else
		{
			$subRequests[] = 'credits';
		}

		if (!in_array('', $options->TvThreads_watchProviderRegions))
		{
			$subRequests[] = 'watch/providers';
		}

		return $subRequests;
	}

	public function insertOrUpdateShowCredits($tvId, $season = 0, $episode = 0, array $rawCredits = [])
	{
		$casts = $rawCredits['cast'] ?? [];
		$crews = $rawCredits['crew'] ?? [];

		$db = $this->db();

		// Save casts & crew
		$castData = [];
		$i = 0;
		foreach ($casts as $cast)
		{
			$i++;
			$castData[$i] = $this->getCastDataForInsert($cast, $tvId, $season, $episode);
		}

		if ($castData)
		{
			$db->insertBulk('xf_snog_tv_cast', $castData, true);
		}

		$crewData = [];
		$i = 0;
		foreach ($crews as $crew)
		{
			$i++;
			$crewData[$i] = $this->getCrewDataForInsert($crew, $tvId, $season, $episode);
		}

		if ($crewData)
		{
			$db->insertBulk('xf_snog_tv_crew', $crewData, true);
		}
	}

	protected function getCastDataForInsert($cast, $tvId, $season = 0, $episode = 0)
	{
		/** @var \Snog\TV\Entity\Cast $entity */
		$entity = $this->em->create('Snog\TV:Cast');

		$entity->bulkSet([
			'tv_id' => $tvId,
			'tv_season' => $season,
			'tv_episode' => $episode,
			'person_id' => $cast['id'],
			'character' => $cast['character'] ?? '',
			'total_episode_count' => $cast['total_episode_count'] ?? 0,
			'credit_id' => $cast['credit_id'] ?? '',
			'known_for_department' => $cast['known_for_department'] ?? '',
			'order' => $cast['order'],
			'roles' => $cast['roles'] ?? []
		], ['forceConstraint' => true]);

		$insertData = $entity->toArray(false);

		$db = $this->db();
		if (!empty($insertData['roles']))
		{
			$insertData['roles'] = $db->quote(json_encode($insertData['roles']));
		}
		else
		{
			$insertData['roles'] = '[]';
		}

		return $insertData;
	}

	protected function getCrewDataForInsert($crew, $tvId, $season = 0, $episode = 0)
	{
		/** @var \Snog\TV\Entity\Crew $entity */
		$entity = $this->em->create('Snog\TV:Crew');

		$entity->bulkSet([
			'tv_id' => $tvId,
			'tv_season' => $season,
			'tv_episode' => $episode,
			'person_id' => $crew['id'],
			'credit_id' => $crew['credit_id'] ?? '',
			'known_for_department' => $crew['known_for_department'] ?? '',
			'total_episode_count' => $cast['total_episode_count'] ?? 0,
			'department' => $crew['department'] ?? '',
			'job' => $crew['job'] ?? '',
			'order' => $crew['order'] ?? 0,
			'jobs' => $cast['jobs'] ?? []
		], ['forceConstraint' => true]);

		$insertData = $entity->toArray(false);

		$db = $this->db();
		if (!empty($insertData['jobs']))
		{
			$insertData['jobs'] = $db->quote(json_encode($insertData['jobs']));
		}
		else
		{
			$insertData['jobs'] = '[]';
		}

		return $insertData;
	}

	public function insertOrUpdateShowVideos($tvId, $season = 0, $episode = 0, $videosApiData = [], $existing = false)
	{
		$db = $this->db();

		$existingVideoIds = $this->db()->fetchAllColumn('
			SELECT video_id
			FROM xf_snog_tv_video
			WHERE tv_id = ?
				AND tv_season = ?
				AND tv_episode = ?
		',  [$tvId, $season, $episode]);

		$videoData = [];
		$i = 0;
		foreach ($videosApiData as $video)
		{
			$i++;
			$videoData[$i] = $this->getVideoDataForInsert($video, $tvId, $season, $episode);
		}

		if ($existing)
		{
			$newVideoIds = array_column($videosApiData, 'video_id');
			$videosIdsToRemove = array_diff(
				$existingVideoIds,
				$newVideoIds
			);

			if ($videosIdsToRemove)
			{
				$this->db()->delete(
					'xf_snog_tv_video',
					'tv_id = ? AND tv_season = ? AND tv_episode = ? AND video_id IN(' . $db->quote($videosIdsToRemove) . ')',
					[$tvId, $season, $episode]
				);
			}
		}

		if ($videoData)
		{
			$db->insertBulk('xf_snog_tv_video', $videoData, true);
		}
	}

	protected function getVideoDataForInsert($video, $tvId, $season = 0, $episode = 0)
	{
		/** @var \Snog\TV\Entity\Video $entity */
		$entity = $this->em->create('Snog\TV:Video');

		$entity->bulkSet([
			'tv_id' => $tvId,
			'tv_season' => $season,
			'tv_episode' => $episode,
			'video_id' => $video['id'] ?? '',
			'name' => $video['name'] ?? '',
			'key' => $video['key'] ?? '',
			'site' => $video['site'] ?? '',
			'size' => $video['size'] ?? 0,
			'type' => $video['type'] ?? '',
			'official' => $video['official'] ?? 0,
			'published_at' => isset($video['published_at']) ? strtotime($video['published_at']) : 0,
			'iso_639_1' => $video['iso_639_1'] ?? '',
			'iso_3166_1' => $video['iso_3166_1'] ?? ''
		], ['forceConstraint' => true]);

		return $entity->toArray(false);
	}
}
