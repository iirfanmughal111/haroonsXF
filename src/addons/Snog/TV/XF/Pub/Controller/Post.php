<?php

namespace Snog\TV\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
	public function actionEdit(ParameterBag $params)
	{
		/** @var \Snog\TV\XF\Entity\Post $post */
		$post = $this->assertViewablePost($params->post_id, ['Thread', 'Thread.TV']);

		$thread = $post->Thread;

		if ($thread->discussion_type === 'snog_tv')
		{
			if ($post->isFirstPost() && $thread->TV)
			{
				return $this->rerouteController('Snog\TV:TV', 'edit', ['thread_id' => $thread->thread_id]);
			}
			elseif ($post->TVPost)
			{
				return $this->rerouteController('Snog\TV:TVPost', 'edit', ['post_id' => $post->post_id]);
			}
		}

		return parent::actionEdit($params);
	}


}