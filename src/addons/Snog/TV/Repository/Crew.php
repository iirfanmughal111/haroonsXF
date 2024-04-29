<?php

namespace Snog\TV\Repository;

class Crew extends \XF\Mvc\Entity\Repository
{
	public function findCrewForList()
	{
		return $this->finder('Snog\TV:Crew')
			->with('Person', true)
			->setDefaultOrder('order', 'DESC');
	}

	/**
	 * @param \Snog\TV\Entity\TV $tv
	 * @return \XF\Mvc\Entity\Finder|\Snog\TV\Finder\Crew
	 */
	public function findCrewForTv(\Snog\TV\Entity\TV $tv)
	{
		return $this->finder('Snog\TV:Crew')
			->with('Person', true)
			->where('tv_id', '=', $tv->tv_id)
			->where('tv_season', '=', $tv->tv_season)
			->where('tv_episode', '=', $tv->tv_episode)
			->setDefaultOrder('order', 'ASC');
	}
}