<?php

namespace nick97\TraktIntegration\XF\ForumType;

use XF\Entity\Forum;

class Discussion extends XFCP_Discussion
{
	public function getPossibleCreatableThreadTypes(Forum $forum): array
	{
		$threadTypes = parent::getPossibleCreatableThreadTypes($forum);
		$threadTypes[] = 'nick97_trakt_movies';
		$threadTypes[] = 'nick97_trakt_tv';

		return  $threadTypes;
	}
}
