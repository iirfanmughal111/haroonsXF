<?php

namespace XFMG\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Media extends AbstractHandler
{
	/**
	 * @param Entity|\XFMG\Entity\MediaItem $content
	 *
	 * @return string|\XF\Phrase
	 */
	public function getContentTitle(Entity $content)
	{
		if ($content->album_id)
		{
			return \XF::phrase('xfmg_media_x_in_album_y', [
				'title' => $content->title,
				'album' => $content->Album->title
			]);
		}
		else if ($content->category_id)
		{
			return \XF::phrase('xfmg_media_x_in_category_y', [
				'title' => $content->title,
				'category' => $content->Category->title
			]);
		}
		else
		{
			return $content->title;
		}
	}

	/**
	 * @return string
	 */
	public function getContentRoute(Entity $content)
	{
		return 'media';
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

		return ['Album', 'Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}
}