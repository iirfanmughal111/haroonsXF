<?php

namespace Snog\Forms\XF\Repository;

class Post extends XFCP_Post
{
	public function findPostsForThreadView(\XF\Entity\Thread $thread, array $limits = [])
	{
		$finder = parent::findPostsForThreadView($thread, $limits);
		$finder->with('Promotions');
		return $finder;
	}

	public function findNextPostsInThread(\XF\Entity\Thread $thread, $newerThan, array $limits = [])
	{
		$finder = parent::findNextPostsInThread($thread, $newerThan, $limits);
		$finder->with('Promotions');
		return $finder;
	}
}