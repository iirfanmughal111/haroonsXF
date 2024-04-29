<?php

namespace Snog\Movies\XF\Entity;

/**
 * COLUMNS
 * @property \Snog\Movies\Entity\Movie $Movie
 */
class Thread extends XFCP_Thread
{
	protected function threadMadeVisible()
	{
		parent::threadMadeVisible();

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->Movie)
		{
			$this->adjustMovieThreadCount(1);
		}
	}

	protected function threadHidden($hardDelete = false)
	{
		parent::threadHidden($hardDelete);

		/** @var User $user */
		$user = $this->User;
		if ($user && $this->Movie)
		{
			$this->adjustMovieThreadCount(-1);
		}
	}

	public function adjustMovieThreadCount($amount)
	{
		if (!$this->user_id || $this->discussion_type == 'redirect')
		{
			return;
		}

		/** @var User $user */
		$user = $this->User;
		if ($user)
		{
			$user->fastUpdate('snog_movies_thread_count', max(0, $user->snog_movies_thread_count + $amount));
		}
	}
}