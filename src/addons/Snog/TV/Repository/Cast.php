<?php

namespace Snog\TV\Repository;

class Cast extends \XF\Mvc\Entity\Repository
{
	/**
	 * @param \Snog\TV\Entity\TV $tv
	 * @return \XF\Mvc\Entity\Finder|\Snog\TV\Finder\Cast
	 */
	public function findCastForTv(\Snog\TV\Entity\TV $tv)
	{
		return $this->finder('Snog\TV:Cast')
			->with('Person', true)
			->where('tv_id', '=', $tv->tv_id)
			->where('tv_season', '=', $tv->tv_season)
			->where('tv_episode', '=', $tv->tv_episode)
			->setDefaultOrder('order', 'ASC');
	}
}
