<?php

namespace nick97\TraktTV\XF\Entity;

/**
 * COLUMNS
 * @property int $trakt_tv_thread_count
 */
class User extends XFCP_User
{
	public function rebuildTvThreadCount()
	{
		if (!$this->user_id) {
			return;
		}

		$count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_thread AS thread
			INNER JOIN nick97_trakt_tv_thread AS movie
				ON movie.thread_id = thread.thread_id
			WHERE user_id = ? AND discussion_state = 'visible'
		", [$this->user_id]);

		$this->fastUpdate('trakt_tv_thread_count', max(0, $count));
	}
}
