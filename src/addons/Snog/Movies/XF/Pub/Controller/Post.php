<?php

namespace Snog\Movies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
	public function actionEdit(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id, ['Thread', 'Thread.Movie']);

		/** @var \Snog\Movies\XF\Entity\Thread $thread */
		$thread = $post->Thread;

		if ($post->isFirstPost() && $thread->discussion_type === 'snog_movies_movie' && $thread->Movie)
		{
			return $this->rerouteController('Snog\Movies:Movies', 'edit', ['thread_id' => $thread->thread_id]);
		}

		return parent::actionEdit($params);
	}
}