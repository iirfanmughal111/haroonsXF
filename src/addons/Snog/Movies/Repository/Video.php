<?php

namespace Snog\Movies\Repository;

use XF\Mvc\Entity\Repository;

class Video extends Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\Snog\Movies\Finder\Video
	 */
	public function findVideosForList()
	{
		return $this->finder('Snog\Movies:Video')
			->setDefaultOrder('published_at');
	}
}