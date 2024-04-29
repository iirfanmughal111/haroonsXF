<?php

namespace nick97\TraktMovies\Finder;

use XF\Mvc\Entity\Finder;

class Video extends Finder
{
	public function forMovie($movieId)
	{
		$this->where('trakt_id', '=', $movieId);
		return $this;
	}

	public function onlyOfficial()
	{
		$this->where('official', '=', 1);
		return $this;
	}
}
