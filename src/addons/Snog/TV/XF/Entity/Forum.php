<?php

namespace Snog\TV\XF\Entity;

/**
 * RELATIONS
 * @property \Snog\TV\Entity\TVForum $TVForum
 */
class Forum extends XFCP_Forum
{
	public function canCreatePoll(&$error = null)
	{
		if ($this->forum_type_id == 'snog_tv')
		{
			return false;
		}

		return parent::canCreatePoll($error);
	}

	public function threadAdded(\XF\Entity\Thread $thread)
	{
		parent::threadAdded($thread);

		/** @var Thread $thread */
		if ($thread->discussion_type == 'snog_tv')
		{
			$thread->adjustTvThreadCount(1);
		}
	}
}