<?php

namespace Snog\TV\XF\Entity;

/**
 * RELATIONS
 * @property \Snog\TV\Entity\TV $TV
 * @property Forum $Forum
 */
class Thread extends XFCP_Thread
{
	protected function threadMadeVisible()
	{
		parent::threadMadeVisible();

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->TV)
		{
			$this->adjustTvThreadCount(1);
		}
	}

	protected function threadHidden($hardDelete = false)
	{
		parent::threadHidden($hardDelete);

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->TV)
		{
			$this->adjustTvThreadCount(-1);
		}
	}

	public function adjustTvThreadCount($amount)
	{
		if (!$this->user_id || $this->discussion_type == 'redirect')
		{
			return;
		}

		/** @var User $user */
		$user = $this->User;
		if ($user)
		{
			$user->fastUpdate('snog_tv_thread_count', max(0, $user->snog_tv_thread_count + $amount));
		}
	}

	protected function _postDeletePosts(array $postIds)
	{
		$this->db()->delete('xf_snog_tv_post', 'post_id IN (' . $this->db()->quote($postIds) . ')');
		parent::_postDeletePosts($postIds);
	}
}