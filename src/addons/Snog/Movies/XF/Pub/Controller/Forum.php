<?php

namespace Snog\Movies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
	public function actionPostThread(ParameterBag $params)
	{
		if ($this->isPost())
		{
			if (!$params->node_id && !$params->node_name)
			{
				return parent::actionPostThread($params);
			}

			$visitor = \XF::visitor();

			/** @var \Snog\Movies\XF\Entity\Forum $forum */
			$forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . $visitor->user_id]);

			if (!$forum->isThreadTypeCreatable('snog_movies_movie'))
			{
				return parent::actionPostThread($params);
			}

			/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
			$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');
			$movieId = $tmdbHelper->parseMovieId($this->filter('snog_movies_tmdb_id', 'str'));

			/** @var \XF\ControllerPlugin\Editor $editorPlugin */
			$editorPlugin = $this->plugin('XF:Editor');
			$comment = $editorPlugin->fromInput('message');

			/** @var \Snog\Movies\Entity\Movie $exists */
			$exists = $this->em()->findOne('Snog\Movies:Movie', ['tmdb_id', $movieId]);

			// Movie already exists - if comments made post to existing thread
			if (isset($exists->tmdb_id) && $comment)
			{
				/** @var \Snog\Movies\XF\Entity\Thread $thread */
				$thread = $exists->getRelationOrDefault('Thread');

				/** @var \XF\Service\Thread\Replier $replier */
				$replier = $this->service('XF:Thread\Replier', $thread);
				$replier->setMessage($comment);
				if ($forum->canUploadAndManageAttachments())
				{
					$replier->setAttachmentHash($this->filter('attachment_hash', 'str'));
				}

				/** @var \XF\Entity\Post $post */
				$post = $replier->save();

				/** @var \XF\ControllerPlugin\Thread $threadPlugin */
				$threadPlugin = $this->plugin('XF:Thread');
				return $this->redirect($threadPlugin->getPostLink($post));
			}

			// Movie already exists - no comments - send to existing thread
			if (isset($exists->tmdb_id))
			{
				$thread = $exists->getRelationOrDefault('Thread');
				return $this->redirect($this->buildLink('threads', $thread));
			}
		}

		return parent::actionPostThread($params);
	}
}