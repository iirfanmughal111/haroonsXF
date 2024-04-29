<?php

namespace nick97\TraktMovies\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
	public function actionPostThread(ParameterBag $params)
	{
		if ($this->isPost()) {
			if (!$params->node_id && !$params->node_name) {
				return parent::actionPostThread($params);
			}

			$visitor = \XF::visitor();

			/** @var \nick97\TraktMovies\XF\Entity\Forum $forum */
			$forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . $visitor->user_id]);

			if ($forum->isThreadTypeCreatable('trakt_movies_movie')) {
				if (!\XF::visitor()->hasPermission('trakt_movies', 'use_trakt_movies')) {
					throw $this->exception($this->noPermission());
				}
			}

			if (!$forum->isThreadTypeCreatable('trakt_movies_movie')) {
				return parent::actionPostThread($params);
			}

			$clientKey = \XF::options()->traktMovieThreads_apikey;

			if (!$clientKey) {
				throw $this->exception(
					$this->notFound(\XF::phrase("nick97_movie_trakt_api_key_not_found"))
				);
			}

			/** @var \nick97\TraktMovies\Helper\Trakt $traktHelper */
			$traktHelper = \XF::helper('nick97\TraktMovies:Trakt');
			$movieId = $traktHelper->parseMovieId($this->filter('nick97_movies_trakt_id', 'str'));

			/** @var \XF\ControllerPlugin\Editor $editorPlugin */
			$editorPlugin = $this->plugin('XF:Editor');
			$comment = $editorPlugin->fromInput('message');

			/** @var \nick97\TraktMovies\Entity\Movie $exists */
			$exists = $this->em()->findOne('nick97\TraktMovies:Movie', ['trakt_id', $movieId]);

			// Movie already exists - if comments made post to existing thread
			if (isset($exists->trakt_id) && $comment) {
				/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
				$thread = $exists->getRelationOrDefault('Thread');

				/** @var \XF\Service\Thread\Replier $replier */
				$replier = $this->service('XF:Thread\Replier', $thread);
				$replier->setMessage($comment);
				if ($forum->canUploadAndManageAttachments()) {
					$replier->setAttachmentHash($this->filter('attachment_hash', 'str'));
				}

				/** @var \XF\Entity\Post $post */
				$post = $replier->save();

				/** @var \XF\ControllerPlugin\Thread $threadPlugin */
				$threadPlugin = $this->plugin('XF:Thread');
				return $this->redirect($threadPlugin->getPostLink($post));
			}

			// Movie already exists - no comments - send to existing thread
			if (isset($exists->trakt_id)) {
				$thread = $exists->getRelationOrDefault('Thread');
				return $this->redirect($this->buildLink('threads', $thread));
			}
		}

		return parent::actionPostThread($params);
	}
}
