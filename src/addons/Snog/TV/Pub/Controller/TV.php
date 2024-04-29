<?php

namespace Snog\TV\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Util\File;

class TV extends AbstractController
{
	public function actionCasts(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['TV']);
		$tv = $thread->TV;
		if (!$tv)
		{
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->TvThreads_castsLimit;

		/** @var \Snog\TV\Repository\Cast $castRepo */
		$castRepo = $this->repository('Snog\TV:Cast');
		$castFinder = $castRepo->findCastForTv($tv)
			->useDefaultOrder()
			->limitByPage($page, $perPage, 1);

		$casts = $castFinder->fetch();

		$hasMore = $casts->count() > $perPage;
		$casts = $casts->slice(0, $perPage);
		$castsTotal = $castFinder->total();

		$viewParams = [
			'tv' => $tv,
			'casts' => $casts,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $castsTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\TV:TV\Casts', 'snog_tv_casts', $viewParams);
	}

	public function actionCrews(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['TV']);
		$tv = $thread->TV;
		if (!$tv)
		{
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->TvThreads_creditsLimit;

		/** @var \Snog\TV\Repository\Crew $crewRepo */
		$crewRepo = $this->repository('Snog\TV:Crew');
		$crewFinder = $crewRepo->findCrewForTv($tv)
			->useDefaultOrder()
			->limitByPage($page, $perPage, 1);

		$crews = $crewFinder->fetch();

		$hasMore = ($crews->count() > $perPage);
		$crews = $crews->slice(0, $perPage);
		$crewsTotal = $crewFinder->total();

		$viewParams = [
			'tv' => $tv,
			'crews' => $crews,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $crewsTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\Movies:TV\Crew', 'snog_tv_crews', $viewParams);
	}

	public function actionVideos(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['TV']);
		$tv = $thread->TV;
		if (!$tv)
		{
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->TvThreads_videosLimit;

		/** @var \Snog\TV\Repository\Video $videoRepo */
		$videoRepo = $this->repository('Snog\TV:Video');
		$videoFinder = $videoRepo->findVideosForList()
			->forShow($tv->tv_id)
			->limitByPage($page, $perPage, 1);

		$videos = $videoFinder->fetch();

		$hasMore = ($videos->count() > $perPage);
		$videos = $videos->slice(0, $perPage);
		$videosTotal = $videoFinder->total();

		$viewParams = [
			'tv' => $tv,
			'videos' => $videos,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $videosTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\TV:TV\Videos', 'snog_tv_videos', $viewParams);
	}

	public function actionRate(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$thread = $this->assertViewableThread($params->thread_id);
		$show = $thread->TV;
		if (!$show)
		{
			return $this->notFound();
		}

		/** @var \Snog\TV\Entity\Rating $rating */
		$userRating = $this->em()->create('Snog\TV:Rating');
		if ($show->hasRated())
		{
			$userRating = $show->Ratings[$visitor->user_id];
		}

		if ($this->isPost())
		{
			$rater = $this->setupShowRate($show);
			if (!$rater->validate($errors))
			{
				return $errors;
			}

			$rater->save();

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = [
			'tvshow' => $show,
			'userRating' => $userRating
		];
		return $this->view('Snog:TV\TV', 'snog_tv_rate', $viewParams);
	}

	protected function setupShowRate(\Snog\TV\Entity\TV $tv)
	{
		/** @var \Snog\TV\Service\TV\Rate $rater */
		$rater = $this->service('Snog\TV:TV\Rate', $tv);
		$rater->setRating($this->filter('rating', 'uint'));

		return $rater;
	}

	public function actionRateShow(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$forum = $this->assertViewableForum($params->node_id);
		$show = $forum->TVForum;
		if (!$show)
		{
			return $this->notFound();
		}

		/** @var \Snog\TV\Entity\RatingNode $rating */
		$userRating = $this->em()->create('Snog\TV:RatingNode');
		if ($show->hasRated())
		{
			$userRating = $show->Ratings[$visitor->user_id];
		}

		if ($this->isPost())
		{
			$rater = $this->setupForumRate($show);
			if (!$rater->validate($errors))
			{
				return $errors;
			}

			$rater->save();

			return $this->redirect($this->buildLink('forums', $forum));
		}

		$viewParams = [
			'tvshow' => $show,
			'userRating' => $userRating
		];
		return $this->view('Snog:TV\TV', 'snog_tv_rate_show', $viewParams);
	}

	protected function setupForumRate(\Snog\TV\Entity\TVForum $tvForum)
	{
		/** @var \Snog\TV\Service\TV\Rate $rater */
		$rater = $this->service('Snog\TV:TVForum\Rate', $tvForum);
		$rater->setRating($this->filter('rating', 'uint'));

		return $rater;
	}

	public function actionEdit(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['TV']);
		$tvShow = $thread->TV;
		if (!$tvShow)
		{
			return $this->notFound();
		}

		$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);

		/** @var \XF\Entity\Post $error */
		if (!$post->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$forum = $post->Thread->Forum;

		if ($this->isPost())
		{
			$editor = $this->setupShowEdit($tvShow);

			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$threadChanges = [];

			/** @var \Snog\TV\Entity\TV $tvShow */
			$tvShow = $editor->save();

			if ($tvShow->tv_title !== $thread->title)
			{
				$threadChanges = ['title' => 1];
			}

			if ($this->filter('_xfWithData', 'bool'))
			{
				$viewParams = ['post' => $post, 'thread' => $thread];

				$reply = $this->view('XF:Post\EditNewPost', 'post_edit_new_post', $viewParams);

				$reply->setJsonParams([
					'message' => \XF::phrase('your_changes_have_been_saved'),
					'threadChanges' => $threadChanges
				]);
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

		$prefix = $thread->Prefix;
		$prefixes = $forum->getUsablePrefixes($prefix);

		$viewParams = [
			'tvshow' => $tvShow,
			'post' => $post,
			'thread' => $thread,
			'forum' => $forum,
			'prefixes' => $prefixes,
			'attachmentData' => $attachmentData,
			'quickEdit' => $this->filter('_xfWithData', 'bool')
		];
		return $this->view('XF:Post\Edit', 'snog_tv_edit_show', $viewParams);
	}

	protected function setupShowEdit(\Snog\TV\Entity\TV $tv)
	{
		/** @var \Snog\TV\Service\TV\Editor $editor */
		$editor = $this->service('Snog\TV:TV\Editor', $tv);

		$input = $this->filter([
			'tv_title' => 'str',
			'tv_genres' => 'str',
			'tv_director' => 'str',
			'tv_cast' => 'str',
			'first_air_date' => 'datetime',
			'last_air_date' => 'datetime',
			'status' => 'str',
			'tv_plot' => 'str'
		]);

		$editor->setTitle($input['tv_title']);
		$editor->setPlot($input['tv_plot']);
		$editor->setGenres($input['tv_genres']);
		$editor->setDirector($input['tv_director']);
		$editor->setCast($input['tv_cast']);
		$editor->setFirstAirDate($input['first_air_date']);
		$editor->setLastAirDate($input['last_air_date']);
		$editor->setStatus($input['status']);

		$url = $this->filter('tv_trailer', 'str');
		$trailer = '';
		if (stristr($url, 'youtube'))
		{
			/** @var \XF\Repository\BbCodeMediaSite $mediaRepo */
			$mediaRepo = $this->repository('XF:BbCodeMediaSite');
			$sites = $mediaRepo->findActiveMediaSites()->fetch();
			$match = $mediaRepo->urlMatchesMediaSiteList($url, $sites);
			if ($match)
			{
				$trailer = $match['media_id'];
			}
		}
		else
		{
			$trailer = $url;
		}
		$editor->setTrailer($trailer);

		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$comment = $editorPlugin->fromInput('message');

		if (!$this->options()->TvThreads_force_comments)
		{
			$editor->setComment($comment);
		}
		else
		{
			$editor->setComment('');
		}

		$tv = $editor->getTv();

		if ($this->app()->options()->TvThreads_syncTitle)
		{
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($tv->getExpectedThreadTitle());
		}

		$postEditor = $editor->getPostEditor();


		$postEditor->setMessage($tv->getPostMessage());

		$forum = $tv->Thread->Forum;
		if ($forum->canUploadAndManageAttachments())
		{
			$postEditor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		return $editor;
	}

	/**
	 * @param $threadId
	 * @param array $extraWith
	 * @return \Snog\TV\XF\Entity\Thread
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableThread($threadId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
		$extraWith[] = 'Forum';
		$extraWith[] = 'Forum.Node';
		$extraWith[] = 'Forum.Node.Permissions|' . $visitor->permission_combination_id;

		if ($visitor->user_id)
		{
			$extraWith[] = 'Read|' . $visitor->user_id;
			$extraWith[] = 'Forum.Read|' . $visitor->user_id;
		}

		/** @var \Snog\TV\XF\Entity\Thread $thread */
		$thread = $this->em()->find('XF:Thread', $threadId, $extraWith);
		if (!$thread)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_thread_not_found')));
		}

		if (!$thread->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		/** @var \XF\ControllerPlugin\Node $nodePlugin */
		$nodePlugin = $this->plugin('XF:Node');
		$nodePlugin->applyNodeContext($thread->Forum->Node);
		$this->setContentKey('thread-' . $thread->thread_id);

		return $thread;
	}

	public function actionPoster(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		if (!$visitor->user_id || (!$visitor->is_moderator && !$visitor->is_admin))
		{
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id, ['TV']);
		$tvShow = $thread->TV;
		if (!$tvShow)
		{
			return $this->notFound();
		}

		if ($this->isPost())
		{
			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$posterPath = $this->filter('posterpath', 'str');

			$db = $this->app->db();
			$db->beginTransaction();

			$tvShow->tv_image = $posterPath;

			if ($this->app->options()->TvThreads_useLocalImages)
			{
				/** @var \Snog\TV\Service\TV\Image $imageService */
				$imageService = $this->app()->service('Snog\TV:TV\Image', $tvShow);
				if (!$imageService->setImageFromApiPath($posterPath))
				{
					return $this->error($imageService->getError());
				}

				$imageService->updateImage();
			}

			$tvShow->save(true, false);

			$post->message = $tvShow->getPostMessage();
			$post->last_edit_date = 0;
			$post->save(true, false);

			$db->commit();

			return $this->redirect($this->buildLink('posts', $post));
		}

		$newPoster = false;
		$posterPath = '';

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$tvInfo = $tmdbClient->getTv($tvShow->tv_id)->getDetails(['credits', 'videos']);

		if ($tmdbClient->hasError())
		{
			return $this->error($tmdbClient->getError());
		}

		if (!isset($tvInfo['id']))
		{
			return $this->error(\XF::phrase('snog_tv_error_not_returned'));
		}

		if (isset($tvInfo['poster_path']))
		{
			$posterPath = $tvInfo['poster_path'];
		}
		if ($posterPath !== $tvShow->tv_image)
		{
			$newPoster = true;
		}

		$viewParams = [
			'tvshow' => $tvShow,
			'newposter' => $newPoster,
			'posterpath' => $posterPath
		];
		return $this->view('Snog\TV:TV', 'snog_tv_new_poster', $viewParams);
	}

	public function actionAddInfo(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$visitor->hasPermission('tvthreads_interface', 'add_info'))
		{
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost())
		{
			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$title = $this->filter('tmdb', 'str');
			$changeTitle = $this->filter('changetitle', 'uint');

			if (!$title)
			{
				return $this->error(\XF::phrase('snog_tv_error_no_show'));
			}

			/** @var \Snog\TV\Helper\Tmdb\Show $tmdbShowHelper */
			$tmdbShowHelper = \XF::helper('Snog\TV:Tmdb\Show');
			$showId = $tmdbShowHelper->parseShowId($title);

			if (stristr($showId, '?'))
			{
				$showIdParts = explode('?', $showId);
				$showId = $showIdParts[0];
			}

			if (!$showId)
			{
				return $this->error(\XF::phrase('snog_tv_error_id_not_valid'));
			}

			$comment = $post->message;

			/** @var \Snog\TV\Entity\TV $existingShow */
			$existingShow = $this->em()->findOne('Snog\TV:TV', ['tv_id' => $showId]);
			if (!$this->options()->TvThreads_multiple && $existingShow)
			{
				return $this->error(\XF::phrase('snog_tv_error_show_posted'));
			}

			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getTv($showId)->getDetails(['credits', 'videos']);
			if ($tmdbClient->hasError())
			{
				return $this->error($tmdbClient->getError());
			}

			/** @var \Snog\TV\Entity\TV $tvShow */
			$tvShow = $this->em()->create('Snog\TV:TV');
			$tvShow->thread_id = $thread->thread_id;
			$tvShow->setFromApiResponse($apiResponse);

			if (isset($apiResponse['poster_path']))
			{
				$tvShow->tv_image = $apiResponse['poster_path'];

				if ($this->app->options()->TvThreads_useLocalImages)
				{
					/** @var \Snog\TV\Service\TV\Image $imageService */
					$imageService = $this->app()->service('Snog\TV:TV\Image', $tvShow);

					$imageService->setImageFromApiPath($apiResponse['poster_path']);
					$imageService->updateImage();
				}
			}

			if ($changeTitle)
			{
				$thread->title = $tvShow->getExpectedThreadTitle();
				$thread->save();
			}

			$post->message = $tvShow->getPostMessage();
			$post->last_edit_date = 0;
			$post->save();

			if ($comment && $this->options()->TvThreads_force_comments)
			{
				$newFirstPost = false;
				$newLastPost = false;

				/** @var \XF\Entity\Post $newPost */
				$newPost = $this->em()->create('XF:Post');
				$newPost->thread_id = $thread->thread_id;
				$newPost->user_id = $thread->user_id;
				$newPost->username = $thread->username;
				$newPost->post_date = $thread->post_date;
				$newPost->message = $comment;
				$newPost->ip_id = $post->ip_id;
				$newPost->position = 1;
				$newPost->last_edit_date = 0;
				$newPost->save();
				$newPostId = $newPost->getEntityId();

				if ($thread->first_post_id > 0 && $thread->first_post_id <> $post->post_id)
				{
					$newFirstPost = true;
				}
				if ($thread->first_post_id == $thread->last_post_id)
				{
					$newLastPost = true;
				}

				if ($newFirstPost)
				{
					$thread->first_post_id = $newPostId;
				}
				if ($newLastPost)
				{
					$thread->last_post_date = $thread->post_date;
					$thread->last_post_id = $newPostId;
					$thread->last_post_user_id = $thread->user_id;
					$thread->last_post_username = $thread->username;
				}

				if ($thread->isChanged('first_post_id') || $thread->isChanged('last_post_id'))
				{
					$thread->save();
				}

				/** @var \XF\Entity\Post[] $postOrder */
				$postOrder = $this->finder('XF:Post')
					->where('thread_id', $thread->thread_id)
					->order('post_date')
					->fetch();

				$order = 1;
				foreach ($postOrder as $changeOrder)
				{
					if ($changeOrder->post_id <> $thread->first_post_id)
					{
						$changeOrder->position = $order;
						$changeOrder->save();
						$order = $order + 1;
					}
				}
			}

			if (!$this->options()->TvThreads_force_comments)
			{
				$tvShow->comment = $comment;
			}

			$tvShow->save(false, false);

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = [
			'thread' => $thread
		];
		return $this->view('Snog\TV:TV', 'snog_tv_add_info', $viewParams);
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

	/**
	 * @param $nodeIdOrName
	 * @param array $extraWith
	 * @return \Snog\TV\XF\Entity\Forum
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableForum($nodeIdOrName, array $extraWith = [])
	{
		if ($nodeIdOrName === null)
		{
			throw new \InvalidArgumentException("Node ID/name not passed in correctly");
		}

		$visitor = \XF::visitor();
		$extraWith[] = 'Node.Permissions|' . $visitor->permission_combination_id;
		if ($visitor->user_id)
		{
			$extraWith[] = 'Read|' . $visitor->user_id;
		}

		$finder = $this->em()->getFinder('XF:Forum');
		$finder->with('Node', true)->with($extraWith);
		if (is_int($nodeIdOrName) || $nodeIdOrName === strval(intval($nodeIdOrName)))
		{
			$finder->where('node_id', $nodeIdOrName);
		}
		else
		{
			$finder->where(['Node.node_name' => $nodeIdOrName, 'Node.node_type_id' => 'Forum']);
		}

		/** @var \Snog\TV\XF\Entity\Forum $forum */
		$forum = $finder->fetchOne();
		if (!$forum)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_forum_not_found')));
		}

		if (!$forum->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		/** @var \XF\ControllerPlugin\Node $nodePlugin */
		$nodePlugin = $this->plugin('XF:Node');
		$nodePlugin->applyNodeContext($forum->Node);

		return $forum;
	}
}