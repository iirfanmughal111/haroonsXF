<?php

namespace Snog\TV\Repository;

class Video extends \XF\Mvc\Entity\Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\Snog\TV\Finder\Video
	 */
	public function findVideosForList()
	{
		return $this->finder('Snog\TV:Video')
			->setDefaultOrder('published_at');
	}
}