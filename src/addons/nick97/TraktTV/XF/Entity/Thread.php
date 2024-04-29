<?php

namespace nick97\TraktTV\XF\Entity;

/**
 * RELATIONS
 * @property \nick97\TraktTV\Entity\TV $TV
 * @property Forum $Forum
 */
class Thread extends XFCP_Thread
{
	protected function threadMadeVisible()
	{
		parent::threadMadeVisible();

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->traktTV) {
			$this->adjustTvThreadCount(1);
		}
	}

	protected function threadHidden($hardDelete = false)
	{
		parent::threadHidden($hardDelete);

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->traktTV) {
			$this->adjustTvThreadCount(-1);
		}
	}

	public function adjustTvThreadCount($amount)
	{
		if (!$this->user_id || $this->discussion_type == 'redirect') {
			return;
		}

		/** @var User $user */
		$user = $this->User;
		if ($user) {
			$user->fastUpdate('trakt_tv_thread_count', max(0, $user->trakt_tv_thread_count + $amount));
		}
	}

	protected function _postDeletePosts(array $postIds)
	{
		$this->db()->delete('nick97_trakt_tv_post', 'post_id IN (' . $this->db()->quote($postIds) . ')');
		parent::_postDeletePosts($postIds);
	}

	// public function getTraktTVLink($id)
	// {
	// 	$recordExist = \XF::finder('nick97\TraktTV:TraktTVSlug')->where('tmdb_id', $id)->fetchOne();

	// 	if ($recordExist) {
	// 		return $recordExist["trakt_slug"];
	// 	}
	// }
}
