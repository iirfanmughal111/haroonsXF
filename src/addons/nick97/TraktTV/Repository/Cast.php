<?php

namespace nick97\TraktTV\Repository;

class Cast extends \XF\Mvc\Entity\Repository
{
	/**
	 * @param \nick97\TraktTV\Entity\TV $tv
	 * @return \XF\Mvc\Entity\Finder|\nick97\TraktTV\Finder\Cast
	 */
	public function findCastForTv(\nick97\TraktTV\Entity\TV $tv)
	{
		return $this->finder('nick97\TraktTV:Cast')
			->with('Person', true)
			->where('tv_id', '=', $tv->tv_id)
			->where('tv_season', '=', $tv->tv_season)
			->where('tv_episode', '=', $tv->tv_episode)
			->setDefaultOrder('order', 'ASC');
	}
}
