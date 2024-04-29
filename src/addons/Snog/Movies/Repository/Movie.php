<?php

namespace Snog\Movies\Repository;

class Movie extends \XF\Mvc\Entity\Repository
{
	public function insertOrUpdateMovieCredits($movieId, array $castsResponse)
	{
		$casts = $castsResponse['cast'] ?? [];
		$crews = $castsResponse['crew'] ?? [];

		// Save casts & crew
		$castData = [];
		foreach ($casts as $cast) {
			$castData[] = $this->getCastDataForInsert($cast, $movieId);
		}
		// echo '<pre>';
		// var_dump($castData);
		// exit;
		if ($castData) {
			$this->db()->insertBulk('xf_snog_movies_cast', $castData, true);
		}

		$crewData = [];
		foreach ($crews as $crew) {
			$crewData[] = $this->getCrewDataForInsert($crew, $movieId);
		}

		if ($crewData) {
			$this->db()->insertBulk('xf_snog_movies_crew', $crewData, true);
		}
	}

	protected function getCastDataForInsert(array $cast, $movieId)
	{
		/** @var \Snog\Movies\Entity\Cast $entity */
		$entity = $this->em->create('Snog\Movies:Cast');

		$entity->bulkSet([
			'tmdb_id' => $movieId,
			'person_id' => $cast['id'],
			'character' => $cast['character'] ?? '',
			'cast_id' => $cast['cast_id'],
			'credit_id' => $cast['credit_id'],
			'known_for_department' => $cast['known_for_department'] ?? '',
			'order' => $cast['order'],
		], ['forceConstraint' => true]);

		return $entity->toArray(false);
	}

	protected function getCrewDataForInsert(array $crew, $movieId)
	{
		/** @var \Snog\Movies\Entity\Crew $entity */
		$entity = $this->em->create('Snog\Movies:Crew');

		$entity->bulkSet([
			'tmdb_id' => $movieId,
			'person_id' => $crew['id'],
			'credit_id' => $crew['credit_id'],
			'known_for_department' => $crew['known_for_department'] ?? '',
			'department' => $crew['department']  ?? '',
			'order' => $crew['order'] ?? 0,
			'job' => $crew['job'] ?? '',
		], ['forceConstraint' => true]);

		return $entity->toArray(false);
	}

	public function deleteAllVideosForMovie($movieId)
	{
		return $this->db()->delete('xf_snog_movies_video', 'tmdb_id = ?', $movieId);
	}

	public function insertOrUpdateMovieVideos($movieId, $videosApiData = [])
	{
		$db = $this->db();

		$videoData = [];
		$i = 0;
		foreach ($videosApiData as $video) {
			$i++;
			$videoData[$i] = $this->getVideoDataForInsert($video, $movieId);
		}

		if ($videoData) {
			$db->insertBulk('xf_snog_movies_video', $videoData, true);
		}
	}

	protected function getVideoDataForInsert($video, $movieId)
	{
		/** @var \Snog\Movies\Entity\Video $entity */
		$entity = $this->em->create('Snog\Movies:Video');

		$entity->bulkSet([
			'tmdb_id' => $movieId,
			'video_id' => $video['id'] ?? '',
			'name' => $video['name'] ?? '',
			'key' => $video['key'] ?? '',
			'site' => $video['site'] ?? '',
			'size' => $video['size'] ?? 0,
			'type' => $video['type'] ?? '',
			'official' => $video['official'] ?? 0,
			'published_at' => isset($video['published_at']) ? strtotime($video['published_at']) : 0,
			'iso_639_1' => $video['iso_639_1'] ?? '',
			'iso_3166_1' => $video['iso_3166_1'] ?? '',
		], ['forceConstraint' => true]);

		return $entity->toArray(false);
	}
}
