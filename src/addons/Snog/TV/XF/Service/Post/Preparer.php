<?php

namespace Snog\TV\XF\Service\Post;

class Preparer extends XFCP_Preparer
{
	/**
	 * @var \Snog\TV\XF\Entity\Post
	 */
	protected $post;

	protected function getMessagePreparer($format = true)
	{
		$preparer = parent::getMessagePreparer($format);

		$post = $this->post;
		if ($post->TVPost && $post->TVPost->tv_episode)
		{
			$preparer->setConstraint('allowEmpty', true);
		}

		return $preparer;
	}

	public function afterInsert()
	{
		parent::afterInsert();

		$post = $this->post;

		if ($post->TVPost && $post->TVPost->tv_episode)
		{
			$episodePost = $post->TVPost;

			if ($this->app->options()->TvThreads_useLocalImages && $episodePost->tv_image)
			{
				/** @var \Snog\TV\Service\TVPost\Image $imageService */
				$imageService = $this->app->service('Snog\TV:TVPost\Image', $episodePost);
				$imageService->setImageFromApiPath($episodePost->tv_image, 'w300');
				$imageService->updateImage();
			}

			$post->message = $episodePost->getPostMessage();

			$post->save();
		}
	}
}
