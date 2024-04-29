<?php

namespace nick97\TraktTV\XF\Entity;

/**
 * RELATIONS
 * @property \nick97\TraktTV\Entity\TVForum $TVForum
 */
class Forum extends XFCP_Forum
{
	public function canCreatePoll(&$error = null)
	{
		if ($this->forum_type_id == 'trakt_tv') {
			return false;
		}

		return parent::canCreatePoll($error);
	}

	public function threadAdded(\XF\Entity\Thread $thread)
	{
		parent::threadAdded($thread);

		/** @var Thread $thread */
		if ($thread->discussion_type == 'trakt_tv') {
			$thread->adjustTvThreadCount(1);
		}
	}
}
