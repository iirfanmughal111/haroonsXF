<?php

namespace nick97\TraktMovies\XF\Entity;

/**
 * COLUMNS
 * @property \nick97\TraktMovies\Entity\Movie $Movie
 */
class Thread extends XFCP_Thread
{
	protected function threadMadeVisible()
	{
		parent::threadMadeVisible();

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->traktMovie) {
			$this->adjustMovieThreadCount(1);
		}
	}

	protected function threadHidden($hardDelete = false)
	{
		parent::threadHidden($hardDelete);

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->traktMovie) {
			$this->adjustMovieThreadCount(-1);
		}
	}

	public function adjustMovieThreadCount($amount)
	{
		if (!$this->user_id || $this->discussion_type == 'redirect') {
			return;
		}

		/** @var User $user */
		$user = $this->User;
		if ($user) {
			$user->fastUpdate('trakt_movies_thread_count', max(0, $user->trakt_movies_thread_count + $amount));
		}
	}

	// public function getTraktMovLink($id)
	// {
	// 	$recordExist = \XF::finder('nick97\TraktMovies:TraktMovSlug')->where('tmdb_id', $id)->fetchOne();

	// 	if ($recordExist) {
	// 		return $recordExist["trakt_slug"];
	// 	}
	// }
}
