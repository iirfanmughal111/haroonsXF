<?php

namespace Snog\TV\Pub\Controller;

use XF\Mvc\ParameterBag;

class TVPost extends \XF\Pub\Controller\AbstractController
{
	public function actionEdit(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id);

		/** @var \XF\Entity\Post $error */
		if (!$post->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$episode = $post->TVPost;
		if (!$episode)
		{
			return $this->notFound();
		}

		$forum = $post->Thread->Forum;
		$thread = $post->Thread;

		if ($this->isPost())
		{
			$editor = $this->setupTvPostEdit($episode);
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeTvPostEdit($editor);

			if ($this->filter('_xfWithData', 'bool'))
			{
				$viewParams = ['post' => $post, 'thread' => $thread];
				$reply = $this->view('XF:Post\EditNewPost', 'post_edit_new_post', $viewParams);

				$reply->setJsonParams(['message' => \XF::phrase('your_changes_have_been_saved')]);
				return $reply;
			}

			return $this->redirect($this->buildLink('posts', $post));
		}

		if ($forum->canUploadAndManageAttachments())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('post', $post);
		}
		else
		{
			$attachmentData = null;
		}

		$viewParams = [
			'tvshow' => $episode,
			'post' => $post,
			'forum' => $forum,
			'attachmentData' => $attachmentData,
			'quickEdit' => $this->filter('_xfWithData', 'bool')
		];
		return $this->view('XF:Post\Edit', 'snog_tv_edit_episode', $viewParams);
	}

	protected function finalizeTvPostEdit(\Snog\TV\Service\TVPost\Editor $editor)
	{
	}

	protected function setupTvPostEdit(\Snog\TV\Entity\TVPost $TVPost)
	{
		/** @var \Snog\TV\Service\TVPost\Editor $editor */
		$editor = $this->service('Snog\TV:TVPost\Editor', $TVPost);

		$editor->setTitle($this->filter('tv_title', 'str'));
		$editor->setSeason($this->filter('tv_season', 'uint'));
		$editor->setEpisode($this->filter('tv_episode', 'uint'));
		$editor->setAirDate($this->filter('tv_aired', 'str'));
		$editor->setGuests($this->filter('tv_guest', 'str'));
		$editor->setPlot($this->filter('tv_plot', 'str'));

		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$comment = $editorPlugin->fromInput('message');
		$editor->setMessage($comment);

		$postEditor = $editor->getPostEditor();

		$forum = $TVPost->Post->Thread->Forum;
		if ($forum->canUploadAndManageAttachments())
		{
			$postEditor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		return $editor;
	}

	public function actionUpdate(ParameterBag $params)
	{
		$post = $this->assertViewablePost($params->post_id);

		/** @var \XF\Entity\Post $error */
		if (!$post->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$episode = $post->TVPost;
		if (!$episode)
		{
			return $this->notFound();
		}

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$apiResponse = $tmdbClient->getTv($episode->getTmdbTvId())
			->getSeason($episode->tv_season)
			->getEpisode($episode->tv_episode)
			->getDetails(['credits']);

		if ($tmdbClient->hasError())
		{
			return $this->error($tmdbClient->getError());
		}

		$episode->setFromApiResponse($apiResponse);

		if ($this->app->options()->TvThreads_useLocalImages && $episode->tv_image)
		{
			/** @var \Snog\TV\Service\TVPost\Image $imageService */
			$imageService = $this->app->service('Snog\TV:TVPost\Image', $episode);
			$imageService->setImageFromApiPath($episode->tv_image, 'w300');
			$imageService->updateImage();
		}

		$post->message = $episode->getPostMessage();

		$episode->addCascadedSave($post);
		$episode->save();

		return $this->redirect($this->buildLink('posts', $post));
	}

	protected function assertViewablePost($postId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
		$extraWith[] = 'Thread';
		$extraWith[] = 'Thread.Forum';
		$extraWith[] = 'Thread.Forum.Node';
		$extraWith[] = 'Thread.Forum.Node.Permissions|' . $visitor->permission_combination_id;

		/** @var \Snog\TV\XF\Entity\Post $post */
		$post = $this->em()->find('XF:Post', $postId, $extraWith);

		if (!$post)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_post_not_found')));
		}

		/** @var \XF\Entity\Post $error */
		if (!$post->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		/** @var \XF\ControllerPlugin\Node $nodePlugin */
		$nodePlugin = $this->plugin('XF:Node');
		$nodePlugin->applyNodeContext($post->Thread->Forum->Node);

		return $post;
	}

}