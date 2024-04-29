<?php

namespace nick97\TraktMovies\Repository;

use XF\Mvc\Entity\Repository;

class Video extends Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\nick97\TraktMovies\Finder\Video
	 */
	public function findVideosForList()
	{
		return $this->finder('nick97\TraktMovies:Video')
			->setDefaultOrder('published_at');
	}
}