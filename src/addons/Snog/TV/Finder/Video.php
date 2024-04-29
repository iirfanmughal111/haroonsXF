<?php

namespace Snog\TV\Finder;

class Video extends \XF\Mvc\Entity\Finder
{
	public function forShow($showId)
	{
		$this->where('tv_id', '=', $showId);
		return $this;
	}

	public function forSeason($seasonId)
	{
		$this->where('tv_season', '=', $seasonId);
		return $this;
	}

	public function forEpisode($episodeId)
	{
		$this->where('tv_episode', '=', $episodeId);
		return $this;
	}

	public function onlyOfficial()
	{
		$this->where('official', '=', 1);
		return $this;
	}
}