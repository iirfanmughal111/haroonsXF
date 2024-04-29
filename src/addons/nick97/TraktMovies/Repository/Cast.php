<?php

namespace nick97\TraktMovies\Repository;

class Cast extends \XF\Mvc\Entity\Repository
{
	public function findCastForMovie($traktId)
	{
		if ($traktId instanceof \nick97\TraktMovies\Entity\Movie)
		{
			$traktId = $traktId->trakt_id;
		}

		return $this->finder('nick97\TraktMovies:Cast')
			->with('Person', true)
			->where('trakt_id', '=', $traktId)
			->setDefaultOrder('order', 'ASC');
	}
}