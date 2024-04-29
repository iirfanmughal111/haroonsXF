<?php

namespace Snog\Movies\XF\Entity;

/**
 * COLUMNS
 * @property int $snog_movies_thread_count
 */
class User extends XFCP_User
{
	public function rebuildMovieThreadCount()
	{
		if (!$this->user_id)
		{
			return;
		}

		$count = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_thread AS thread
			INNER JOIN xf_snog_movies_thread AS movie
				ON movie.thread_id = thread.thread_id
			WHERE user_id = ? AND discussion_state = 'visible'
		", [$this->user_id]);

		$this->fastUpdate('snog_movies_thread_count', max(0, $count));
	}
}