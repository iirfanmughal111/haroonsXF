<?php

namespace nick97\TraktMovies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
	public function actionEdit(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id, ['Thread', 'Thread.traktMovie']);

		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
		$thread = $post->Thread;

		if ($post->isFirstPost() && $thread->discussion_type === 'trakt_movies_movie' && $thread->traktMovie) {
			return $this->rerouteController('nick97\TraktMovies:Movies', 'edit', ['thread_id' => $thread->thread_id]);
		}

		return parent::actionEdit($params);
	}
}
