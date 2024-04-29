<?php

namespace Snog\Movies\XF\Entity;

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

		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		if ($thread->discussion_type == 'snog_movies_movie')
		{
			$thread->adjustMovieThreadCount(1);
		}
	}


	public function getLdStructuredData($threads, int $page, array $extraData = [])
	{
		$typeHandler = $this->TypeHandler;
		if ($typeHandler instanceof \Snog\Movies\ForumType\Movie)
		{
			return $typeHandler->getLdStructuredData($this, $threads, $page, $extraData);
		}

		return [];
	}

	public function canCreatePoll(&$error = null)
	{
		if ($this->forum_type_id == 'snog_movies_movie')
		{
			return false;
		}

		return parent::canCreatePoll($error);
	}

	public function inMovieGenreAllowed($genre): bool
	{
		$typeHandler = $this->TypeHandler;
		if ($typeHandler instanceof \Snog\Movies\ForumType\Movie)
		{
			return $typeHandler->isGenreAllowed($this, $genre);
		}

		return false;
	}
}