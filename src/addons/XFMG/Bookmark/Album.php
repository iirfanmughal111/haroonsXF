<?php

namespace XFMG\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Album extends AbstractHandler
{
	/**
	 * @param Entity|\XFMG\Entity\Album $content
	 *
	 * @return string|\XF\Phrase
	 */
	public function getContentTitle(Entity $content)
	{
		return \XF::phrase('xfmg_album_x', [
			'album' => $content->title
		]);
	}

	/**
	 * @return string
	 */
	public function getContentRoute(Entity $content)
	{
		return 'media/albums';
	}

	/**
	 * @return string
	 */
	public function getCustomIconTemplateName()
	{
		return 'public:xfmg_bookmark_custom_icon';
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}
}