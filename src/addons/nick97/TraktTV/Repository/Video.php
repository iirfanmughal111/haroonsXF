<?php

namespace nick97\TraktTV\Repository;

class Video extends \XF\Mvc\Entity\Repository
{
	/**
	 * @return \XF\Mvc\Entity\Finder|\nick97\TraktTV\Finder\Video
	 */
	public function findVideosForList()
	{
		return $this->finder('nick97\TraktTV:Video')
			->setDefaultOrder('published_at');
	}
}
