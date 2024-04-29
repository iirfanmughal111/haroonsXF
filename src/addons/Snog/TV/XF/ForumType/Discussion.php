<?php

namespace Snog\TV\XF\ForumType;

use XF\Entity\Forum;

class Discussion extends XFCP_Discussion
{
	public function getPossibleCreatableThreadTypes(Forum $forum): array
	{
		$threadTypes = parent::getPossibleCreatableThreadTypes($forum);
		$threadTypes[] = 'snog_tv';

		return  $threadTypes;
	}
}