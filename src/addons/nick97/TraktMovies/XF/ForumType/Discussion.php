<?php

namespace nick97\TraktMovies\XF\ForumType;

use XF\Entity\Forum;

class Discussion extends XFCP_Discussion
{
	public function getPossibleCreatableThreadTypes(Forum $forum): array
	{
		$threadTypes = parent::getPossibleCreatableThreadTypes($forum);
		$threadTypes[] = 'trakt_movies_movie';

		return  $threadTypes;
	}
}
