<?php

namespace Snog\Movies\Repository;

class Cast extends \XF\Mvc\Entity\Repository
{
	public function findCastForMovie($tmdbId)
	{
		if ($tmdbId instanceof \Snog\Movies\Entity\Movie)
		{
			$tmdbId = $tmdbId->tmdb_id;
		}

		return $this->finder('Snog\Movies:Cast')
			->with('Person', true)
			->where('tmdb_id', '=', $tmdbId)
			->setDefaultOrder('order', 'ASC');
	}
}