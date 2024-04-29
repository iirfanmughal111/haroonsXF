<?php

namespace nick97\TraktTV\Repository;

class Crew extends \XF\Mvc\Entity\Repository
{
	public function findCrewForList()
	{
		return $this->finder('nick97\TraktTV:Crew')
			->with('Person', true)
			->setDefaultOrder('order', 'DESC');
	}

	/**
	 * @param \nick97\TraktTV\Entity\TV $tv
	 * @return \XF\Mvc\Entity\Finder|\nick97\TraktTV\Finder\Crew
	 */
	public function findCrewForTv(\nick97\TraktTV\Entity\TV $tv)
	{
		return $this->finder('nick97\TraktTV:Crew')
			->with('Person', true)
			->where('tv_id', '=', $tv->tv_id)
			->where('tv_season', '=', $tv->tv_season)
			->where('tv_episode', '=', $tv->tv_episode)
			->setDefaultOrder('order', 'ASC');
	}
}
