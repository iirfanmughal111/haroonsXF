<?php

namespace nick97\TraktMovies\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string $default_sort_order
 */
class Forum extends XFCP_Forum
{
	public function threadAdded(\XF\Entity\Thread $thread)
	{
		parent::threadAdded($thread);

		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		if ($thread->discussion_type == 'trakt_movies_movie') {
			$thread->adjustMovieThreadCount(1);
		}
	}


	public function getLdStructuredData($threads, int $page, array $extraData = [])
	{
		$typeHandler = $this->TypeHandler;
		if ($typeHandler instanceof \nick97\TraktMovies\ForumType\Movie) {
			return $typeHandler->getLdStructuredData($this, $threads, $page, $extraData);
		}

		return [];
	}

	public function canCreatePoll(&$error = null)
	{
		if ($this->forum_type_id == 'trakt_movies_movie') {
			return false;
		}

		return parent::canCreatePoll($error);
	}

	public function inMovieGenreAllowed($genre): bool
	{
		$typeHandler = $this->TypeHandler;
		if ($typeHandler instanceof \nick97\TraktMovies\ForumType\Movie) {
			return $typeHandler->isGenreAllowed($this, $genre);
		}

		return false;
	}
}
