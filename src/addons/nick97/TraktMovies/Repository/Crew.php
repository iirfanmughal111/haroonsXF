<?php

namespace nick97\TraktMovies\Repository;

class Crew extends \XF\Mvc\Entity\Repository
{
	public function findCrewForList()
	{
		return $this->finder('nick97\TraktMovies:Crew')
			->with('Person', true)
			->setDefaultOrder('order', 'DESC');
	}

	public function findCrewForMovie($traktId)
	{
		if ($traktId instanceof \nick97\TraktMovies\Entity\Movie)
		{
			$traktId = $traktId->trakt_id;
		}

		return $this->finder('nick97\TraktMovies:Crew')
			->with('Person', true)
			->where('trakt_id', '=', $traktId)
			->setDefaultOrder('order', 'ASC');
	}
}