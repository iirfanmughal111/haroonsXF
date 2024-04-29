<?php

namespace nick97\TraktTV\XF\Service\Post;

class Preparer extends XFCP_Preparer
{
	/**
	 * @var \nick97\TraktTV\XF\Entity\Post
	 */
	protected $post;

	protected function getMessagePreparer($format = true)
	{
		$preparer = parent::getMessagePreparer($format);

		$post = $this->post;
		if ($post->TVPost && $post->TVPost->tv_episode) {
			$preparer->setConstraint('allowEmpty', true);
		}

		return $preparer;
	}

	public function afterInsert()
	{
		parent::afterInsert();

		$post = $this->post;

		if ($post->TVPost && $post->TVPost->tv_episode) {
			$episodePost = $post->TVPost;

			if ($this->app->options()->traktTvThreads_useLocalImages && $episodePost->tv_image) {
				/** @var \nick97\TraktTV\Service\TVPost\Image $imageService */
				$imageService = $this->app->service('nick97\TraktTV:TVPost\Image', $episodePost);
				$imageService->setImageFromApiPath($episodePost->tv_image, 'w300');
				$imageService->updateImage();
			}

			$post->message = $episodePost->getPostMessage();

			$post->save();
		}
	}
}
