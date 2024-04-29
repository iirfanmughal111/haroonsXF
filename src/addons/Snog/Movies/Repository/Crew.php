<?php

namespace Snog\Movies\Repository;

class Crew extends \XF\Mvc\Entity\Repository
{
	public function findCrewForList()
	{
		return $this->finder('Snog\Movies:Crew')
			->with('Person', true)
			->setDefaultOrder('order', 'DESC');
	}

	public function findCrewForMovie($tmdbId)
	{
		if ($tmdbId instanceof \Snog\Movies\Entity\Movie)
		{
			$tmdbId = $tmdbId->tmdb_id;
		}

		return $this->finder('Snog\Movies:Crew')
			->with('Person', true)
			->where('tmdb_id', '=', $tmdbId)
			->setDefaultOrder('order', 'ASC');
	}
}