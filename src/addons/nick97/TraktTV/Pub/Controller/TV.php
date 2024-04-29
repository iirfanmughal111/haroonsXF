<?php

namespace nick97\TraktTV\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Util\File;

class TV extends AbstractController
{
	public function actionCasts(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktTV']);
		$tv = $thread->traktTV;
		if (!$tv) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktTvThreads_castsLimit;

		/** @var \nick97\TraktTV\Repository\Cast $castRepo */
		$castRepo = $this->repository('nick97\TraktTV:Cast');
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
		return $this->view('nick97\TraktTV:TV\Casts', 'trakt_tv_casts', $viewParams);
	}

	public function actionCrews(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktTV']);
		$tv = $thread->traktTV;
		if (!$tv) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktTvThreads_creditsLimit;

		/** @var \nick97\TraktTV\Repository\Crew $crewRepo */
		$crewRepo = $this->repository('nick97\TraktTV:Crew');
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
		return $this->view('nick97\TraktTV:TV\Crew', 'trakt_tv_crews', $viewParams);
	}

	public function actionVideos(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktTV']);
		$tv = $thread->traktTV;
		if (!$tv) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktTvThreads_videosLimit;

		/** @var \nick97\TraktTV\Repository\Video $videoRepo */
		$videoRepo = $this->repository('nick97\TraktTV:Video');
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
		return $this->view('nick97\TraktTV:TV\Videos', 'trakt_tv_videos', $viewParams);
	}

	public function actionRate(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$thread = $this->assertViewableThread($params->thread_id);
		$show = $thread->traktTV;
		if (!$show) {
			return $this->notFound();
		}

		/** @var \nick97\TraktTV\Entity\Rating $rating */
		$userRating = $this->em()->create('nick97\TraktTV:Rating');
		if ($show->hasRated()) {
			$userRating = $show->Ratings[$visitor->user_id];
		}

		if ($this->isPost()) {
			$rater = $this->setupShowRate($show);
			if (!$rater->validate($errors)) {
				return $errors;
			}

			$rater->save();

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = [
			'tvshow' => $show,
			'userRating' => $userRating
		];
		return $this->view('Snog:TV\TV', 'trakt_tv_rate', $viewParams);
	}

	protected function setupShowRate(\nick97\TraktTV\Entity\TV $tv)
	{
		/** @var \nick97\TraktTV\Service\TV\Rate $rater */
		$rater = $this->service('nick97\TraktTV:TV\Rate', $tv);
		$rater->setRating($this->filter('rating', 'uint'));

		return $rater;
	}

	public function actionRateShow(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$forum = $this->assertViewableForum($params->node_id);
		$show = $forum->TVForum;
		if (!$show) {
			return $this->notFound();
		}

		/** @var \nick97\TraktTV\Entity\RatingNode $rating */
		$userRating = $this->em()->create('nick97\TraktTV:RatingNode');
		if ($show->hasRated()) {
			$userRating = $show->Ratings[$visitor->user_id];
		}

		if ($this->isPost()) {
			$rater = $this->setupForumRate($show);
			if (!$rater->validate($errors)) {
				return $errors;
			}

			$rater->save();

			return $this->redirect($this->buildLink('forums', $forum));
		}

		$viewParams = [
			'tvshow' => $show,
			'userRating' => $userRating
		];
		return $this->view('Snog:TV\TV', 'trakt_tv_rate_show', $viewParams);
	}

	protected function setupForumRate(\nick97\TraktTV\Entity\TVForum $tvForum)
	{
		/** @var \nick97\TraktTV\Service\TV\Rate $rater */
		$rater = $this->service('nick97\TraktTV:TVForum\Rate', $tvForum);
		$rater->setRating($this->filter('rating', 'uint'));

		return $rater;
	}

	public function actionEdit(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktTV']);
		$tvShow = $thread->traktTV;
		if (!$tvShow) {
			return $this->notFound();
		}

		$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);

		/** @var \XF\Entity\Post $error */
		if (!$post->canEdit($error)) {
			return $this->noPermission($error);
		}

		$forum = $post->Thread->Forum;

		if ($this->isPost()) {
			$editor = $this->setupShowEdit($tvShow);

			if (!$editor->validate($errors)) {
				return $this->error($errors);
			}
			$threadChanges = [];

			/** @var \nick97\TraktTV\Entity\TV $tvShow */
			$tvShow = $editor->save();

			if ($tvShow->tv_title !== $thread->title) {
				$threadChanges = ['title' => 1];
			}

			if ($this->filter('_xfWithData', 'bool')) {
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

		if ($forum->canUploadAndManageAttachments()) {
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('post', $post);
		} else {
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
		return $this->view('XF:Post\Edit', 'trakt_tv_edit_show', $viewParams);
	}

	protected function setupShowEdit(\nick97\TraktTV\Entity\TV $tv)
	{
		/** @var \nick97\TraktTV\Service\TV\Editor $editor */
		$editor = $this->service('nick97\TraktTV:TV\Editor', $tv);

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
		if (stristr($url, 'youtube')) {
			/** @var \XF\Repository\BbCodeMediaSite $mediaRepo */
			$mediaRepo = $this->repository('XF:BbCodeMediaSite');
			$sites = $mediaRepo->findActiveMediaSites()->fetch();
			$match = $mediaRepo->urlMatchesMediaSiteList($url, $sites);
			if ($match) {
				$trailer = $match['media_id'];
			}
		} else {
			$trailer = $url;
		}
		$editor->setTrailer($trailer);

		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$comment = $editorPlugin->fromInput('message');

		if (!$this->options()->traktTvThreads_force_comments) {
			$editor->setComment($comment);
		} else {
			$editor->setComment('');
		}

		$tv = $editor->getTv();

		if ($this->app()->options()->traktTvThreads_syncTitle) {
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($tv->getExpectedThreadTitle());
		}

		$postEditor = $editor->getPostEditor();


		$postEditor->setMessage($tv->getPostMessage());

		$forum = $tv->Thread->Forum;
		if ($forum->canUploadAndManageAttachments()) {
			$postEditor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		return $editor;
	}

	/**
	 * @param $threadId
	 * @param array $extraWith
	 * @return \nick97\TraktTV\XF\Entity\Thread
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableThread($threadId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
		$extraWith[] = 'Forum';
		$extraWith[] = 'Forum.Node';
		$extraWith[] = 'Forum.Node.Permissions|' . $visitor->permission_combination_id;

		if ($visitor->user_id) {
			$extraWith[] = 'Read|' . $visitor->user_id;
			$extraWith[] = 'Forum.Read|' . $visitor->user_id;
		}

		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$thread = $this->em()->find('XF:Thread', $threadId, $extraWith);
		if (!$thread) {
			throw $this->exception($this->notFound(\XF::phrase('requested_thread_not_found')));
		}

		if (!$thread->canView($error)) {
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

		if (!$visitor->user_id || (!$visitor->is_moderator && !$visitor->is_admin)) {
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id, ['traktTV']);
		$tvShow = $thread->traktTV;
		if (!$tvShow) {
			return $this->notFound();
		}

		if ($this->isPost()) {
			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$posterPath = $this->filter('posterpath', 'str');

			$db = $this->app->db();
			$db->beginTransaction();

			$tvShow->tv_image = $posterPath;

			if ($this->app->options()->traktTvThreads_useLocalImages) {
				/** @var \nick97\TraktTV\Service\TV\Image $imageService */
				$imageService = $this->app()->service('nick97\TraktTV:TV\Image', $tvShow);
				if (!$imageService->setImageFromApiPath($posterPath)) {
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

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$tvInfo = $traktClient->getTv($tvShow->tv_id)->getDetails(['credits', 'videos']);

		if ($traktClient->hasError()) {
			return $this->error($traktClient->getError());
		}

		if (!isset($tvInfo['id'])) {
			return $this->error(\XF::phrase('trakt_tv_error_not_returned'));
		}

		if (isset($tvInfo['poster_path'])) {
			$posterPath = $tvInfo['poster_path'];
		}
		if ($posterPath !== $tvShow->tv_image) {
			$newPoster = true;
		}

		$viewParams = [
			'tvshow' => $tvShow,
			'newposter' => $newPoster,
			'posterpath' => $posterPath
		];
		return $this->view('nick97\TraktTV:TV', 'trakt_tv_new_poster', $viewParams);
	}

	public function actionAddInfo(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id || !$visitor->hasPermission('trakt_tvthreads_interface', 'add_info')) {
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost()) {
			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$title = $this->filter('trakt', 'str');
			$changeTitle = $this->filter('changetitle', 'uint');

			if (!$title) {
				return $this->error(\XF::phrase('trakt_tv_error_no_show'));
			}

			$clientKey = \XF::options()->traktTvThreads_clientkey;

			if (!$clientKey) {
				throw $this->exception(
					$this->notFound(\XF::phrase("nick97_tv_trakt_api_key_not_found"))
				);
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Show $traktShowHelper */
			$traktShowHelper = \XF::helper('nick97\TraktTV:Trakt\Show');
			$showId = $traktShowHelper->parseShowId($title);

			if (stristr($showId, '?')) {
				$showIdParts = explode('?', $showId);
				$showId = $showIdParts[0];
			}

			if (!$showId) {
				return $this->error(\XF::phrase('trakt_tv_error_id_not_valid'));
			}

			$comment = $post->message;

			/** @var \nick97\TraktTV\Entity\TV $existingShow */
			$existingShow = $this->em()->findOne('nick97\TraktTV:TV', ['tv_id' => $showId]);
			if (!$this->options()->traktTvThreads_multiple && $existingShow) {
				return $this->error(\XF::phrase('trakt_tv_error_show_posted'));
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$apiResponse = $traktClient->getTv($showId)->getDetails(['credits', 'videos']);
			if ($traktClient->hasError()) {
				return $this->error($traktClient->getError());
			}

			/** @var \nick97\TraktTV\Entity\TV $tvShow */
			$tvShow = $this->em()->create('nick97\TraktTV:TV');
			$tvShow->thread_id = $thread->thread_id;
			$tvShow->setFromApiResponse($apiResponse);

			if (isset($apiResponse['poster_path'])) {
				$tvShow->tv_image = $apiResponse['poster_path'];

				if ($this->app->options()->traktTvThreads_useLocalImages) {
					/** @var \nick97\TraktTV\Service\TV\Image $imageService */
					$imageService = $this->app()->service('nick97\TraktTV:TV\Image', $tvShow);

					$imageService->setImageFromApiPath($apiResponse['poster_path']);
					$imageService->updateImage();
				}
			}

			if ($changeTitle) {
				$thread->title = $tvShow->getExpectedThreadTitle();
				$thread->save();
			}

			$post->message = $tvShow->getPostMessage();
			$post->last_edit_date = 0;
			$post->save();

			if ($comment && $this->options()->traktTvThreads_force_comments) {
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

				if ($thread->first_post_id > 0 && $thread->first_post_id <> $post->post_id) {
					$newFirstPost = true;
				}
				if ($thread->first_post_id == $thread->last_post_id) {
					$newLastPost = true;
				}

				if ($newFirstPost) {
					$thread->first_post_id = $newPostId;
				}
				if ($newLastPost) {
					$thread->last_post_date = $thread->post_date;
					$thread->last_post_id = $newPostId;
					$thread->last_post_user_id = $thread->user_id;
					$thread->last_post_username = $thread->username;
				}

				if ($thread->isChanged('first_post_id') || $thread->isChanged('last_post_id')) {
					$thread->save();
				}

				/** @var \XF\Entity\Post[] $postOrder */
				$postOrder = $this->finder('XF:Post')
					->where('thread_id', $thread->thread_id)
					->order('post_date')
					->fetch();

				$order = 1;
				foreach ($postOrder as $changeOrder) {
					if ($changeOrder->post_id <> $thread->first_post_id) {
						$changeOrder->position = $order;
						$changeOrder->save();
						$order = $order + 1;
					}
				}
			}

			if (!$this->options()->traktTvThreads_force_comments) {
				$tvShow->comment = $comment;
			}

			$tvShow->save(false, false);

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = [
			'thread' => $thread
		];
		return $this->view('nick97\TraktTV:TV', 'trakt_tv_add_info', $viewParams);
	}

	protected function assertViewablePost($postId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
		$extraWith[] = 'Thread';
		$extraWith[] = 'Thread.Forum';
		$extraWith[] = 'Thread.Forum.Node';
		$extraWith[] = 'Thread.Forum.Node.Permissions|' . $visitor->permission_combination_id;

		/** @var \nick97\TraktTV\XF\Entity\Post $post */
		$post = $this->em()->find('XF:Post', $postId, $extraWith);

		if (!$post) {
			throw $this->exception($this->notFound(\XF::phrase('requested_post_not_found')));
		}

		/** @var \XF\Entity\Post $error */
		if (!$post->canView($error)) {
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
	 * @return \nick97\TraktTV\XF\Entity\Forum
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableForum($nodeIdOrName, array $extraWith = [])
	{
		if ($nodeIdOrName === null) {
			throw new \InvalidArgumentException("Node ID/name not passed in correctly");
		}

		$visitor = \XF::visitor();
		$extraWith[] = 'Node.Permissions|' . $visitor->permission_combination_id;
		if ($visitor->user_id) {
			$extraWith[] = 'Read|' . $visitor->user_id;
		}

		$finder = $this->em()->getFinder('XF:Forum');
		$finder->with('Node', true)->with($extraWith);
		if (is_int($nodeIdOrName) || $nodeIdOrName === strval(intval($nodeIdOrName))) {
			$finder->where('node_id', $nodeIdOrName);
		} else {
			$finder->where(['Node.node_name' => $nodeIdOrName, 'Node.node_type_id' => 'Forum']);
		}

		/** @var \nick97\TraktTV\XF\Entity\Forum $forum */
		$forum = $finder->fetchOne();
		if (!$forum) {
			throw $this->exception($this->notFound(\XF::phrase('requested_forum_not_found')));
		}

		if (!$forum->canView($error)) {
			throw $this->exception($this->noPermission($error));
		}

		/** @var \XF\ControllerPlugin\Node $nodePlugin */
		$nodePlugin = $this->plugin('XF:Node');
		$nodePlugin->applyNodeContext($forum->Node);

		return $forum;
	}

	// public function actionSync(ParameterBag $params)
	// {
	// 	$visitor = \XF::visitor();
	// 	if (!$visitor->user_id) {
	// 		return $this->noPermission();
	// 	}

	// 	$thread = $this->assertViewableThread($params->thread_id);




	// 	$typeCreator = \XF::service('nick97\TraktTV:Thread\TypeData\TvCreator', $thread, $thread->thread_id);


	// 	$tvCreator = $typeCreator->getTvCreator();

	// 	// $tvId = $thread->traktTV->tv_id;
	// 	$tvId = 87917;

	// 	$threadId = $thread->thread_id;

	// 	// var_dump($tvId,$threadId);exit;

	// 	$tvCreator->setTvId($tvId);

	// 	$thread->setOption('tvData', $tvCreator->getTvData());

	// 	$tvCreator->save();

	// }

	// public function actionSync(ParameterBag $params)
	// {
	// 	$visitor = \XF::visitor();
	// 	if (!$visitor->user_id) {
	// 		return $this->noPermission();
	// 	}

	// 	$thread = $this->assertViewableThread($params->thread_id);


	// 	if ($this->isPost()) {
	// 		$typeCreator = \XF::service('nick97\TraktTV:Thread\TypeData\TvCreator', $thread, 42525);


	// 		$tvCreator = $typeCreator->getTvCreator();

	// 		$tvId = $thread->traktTV->tv_id;

	// 		$threadId = $thread->thread_id;

	// 		\xf::db()->delete('nick97_trakt_tv_thread', 'thread_id = ?', $thread->thread_id);

	// 		\xf::db()->delete('nick97_trakt_tv_crew', 'tv_id = ?', $tvId);
	// 		\xf::db()->delete('nick97_trakt_tv_cast', 'tv_id = ?', $tvId);


	// 		$casts = $this->finder('nick97\TraktTV:Cast')->where('tv_id', $tvId)->fetch();

	// 		if (count($casts)) {

	// 			$this->deleteMovies($casts);
	// 		}

	// 		$Crews = $this->finder('nick97\TraktTV:Crew')->where('tv_id', $tvId)->fetch();

	// 		if (count($Crews)) {

	// 			$this->deleteMovies($Crews);
	// 		}

	// 		$Ratings = $this->finder('nick97\TraktTV:Rating')->where('thread_id', $thread->thread_id)->fetch();

	// 		if (count($Ratings)) {

	// 			$this->deleteMovies($Ratings);
	// 		}

	// 		$tvCreator->setTvId($tvId);

	// 		$thread->setOption('tvData', $tvCreator->getTvData());

	// 		$tvCreator->save();

	// 		$movie = $this->finder('nick97\TraktTV:TV')->where('thread_id', 42525)->fetchOne();
	// 		$movie->fastUpdate('thread_id', $threadId);
	// 		$thread->fastUpdate('title', $thread->traktTV->tv_title);

	// 		return $this->redirect($this->buildLink('threads', $thread));
	// 	} else {
	// 		$viewParams = [
	// 			'thread' => $thread,
	// 		];
	// 		return $this->view('nick97\TraktTV:TV\Sync', 'nick97_trakt_tv_sync_confirm', $viewParams);
	// 	}
	// }

	public function deleteMovies($datas)
	{

		foreach ($datas as $data) {

			$data->delete();
		}
	}
}
