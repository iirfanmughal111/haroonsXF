<?php

namespace Snog\Movies\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Movies extends AbstractController
{
	public function actionCasts(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['Movie']);
		$movie = $thread->Movie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->tmdbthreads_castsLimit;

		/** @var \Snog\Movies\Repository\Cast $castRepo */
		$castRepo = \XF::repository('Snog\Movies:Cast');
		$castFinder = $castRepo->findCastForMovie($movie->tmdb_id)
			->limitByPage($page, $perPage, 1);

		$casts = $castFinder->fetch();

		$hasMore = ($casts->count() > $perPage);
		$casts = $casts->slice(0, $perPage);
		$castsTotal = $castFinder->total();

		$viewParams = [
			'movie' => $movie,
			'casts' => $casts,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $castsTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\Movies:Movies\Casts', 'snog_movies_casts', $viewParams);
	}

	public function actionCrews(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['Movie']);
		$movie = $thread->Movie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->tmdbthreads_crewLimit;

		/** @var \Snog\Movies\Repository\Crew $crewRepo */
		$crewRepo = \XF::repository('Snog\Movies:Crew');
		$crewFinder = $crewRepo->findCrewForMovie($movie->tmdb_id)
			->limitByPage($page, $perPage, 1);

		$crews = $crewFinder->fetch();

		$hasMore = ($crews->count() > $perPage);
		$crews = $crews->slice(0, $perPage);
		$crewsTotal = $crewFinder->total();

		$viewParams = [
			'movie' => $movie,
			'crews' => $crews,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $crewsTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\Movies:Movies\Crew', 'snog_movies_crews', $viewParams);
	}

	public function actionVideos(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['Movie']);
		$movie = $thread->Movie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->tmdbthreads_videoLimit;

		/** @var \Snog\Movies\Repository\Video $videoRepo */
		$videoRepo = \XF::repository('Snog\Movies:Video');
		$videoFinder = $videoRepo->findVideosForList()
			->forMovie($movie->tmdb_id)
			->limitByPage($page, $perPage, 1);

		$videos = $videoFinder->fetch();

		$hasMore = ($videos->count() > $perPage);
		$videos = $videos->slice(0, $perPage);
		$videosTotal = $videoFinder->total();

		$viewParams = [
			'movie' => $movie,
			'videos' => $videos,
			'page' => $page,
			'perPage' => $perPage,
			'total' => $videosTotal,
			'hasMore' => $hasMore
		];
		return $this->view('Snog\Movies:Movies\Videos', 'snog_movies_videos', $viewParams);
	}

	public function actionRate(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$thread = $this->assertViewableThread($params->thread_id, ['Movie']);
		$movie = $thread->Movie;

		if (!$movie) {
			return $this->notFound();
		}

		/** @var \Snog\Movies\Entity\Rating $userRating */
		$userRating = $this->em()->create('Snog\Movies:Rating');
		if ($movie->hasRated()) {
			$userRating = $movie->Ratings[$visitor->user_id];
		}

		if ($this->isPost()) {
			$rater = $this->setupMovieRate($movie);
			if (!$rater->validate($errors)) {
				return $errors;
			}

			$rater->save();

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = ['movie' => $movie, 'userRating' => $userRating];
		return $this->view('Snog:Movies\Movie', 'snog_movies_rate', $viewParams);
	}

	protected function setupMovieRate(\Snog\Movies\Entity\Movie $movie)
	{
		/** @var \Snog\Movies\Service\Movie\Rate $rater */
		$rater = $this->service('Snog\Movies:Movie\Rate', $movie);
		$rater->setRating($this->filter('rating', 'uint'));

		return $rater;
	}

	public function actionEdit(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id);
		$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);

		if (!$post->canEdit($error)) {
			return $this->noPermission($error);
		}

		$movie = $this->assertMovieExists($params->thread_id);

		if ($this->isPost()) {
			$editor = $this->setupMovieEdit($movie);
			if (!$editor->validate($errors)) {
				return $this->error($errors);
			}

			$threadChanges = [];
			$newThread = $editor->getThread();
			if ($newThread) {
				$threadChanges['title'] = $newThread->isChanged('title');
			}

			$editor->save();

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

		$forum = $post->Thread->Forum;
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
			'movie' => $movie,
			'post' => $post,
			'thread' => $thread,
			'forum' => $forum,
			'prefixes' => $prefixes,
			'attachmentData' => $attachmentData,
			'quickEdit' => $this->filter('_xfWithData', 'bool')
		];
		return $this->view('XF:Post\Edit', 'snog_movies_movie_edit', $viewParams);
	}

	protected function setupMovieEdit(\Snog\Movies\Entity\Movie $movie)
	{
		/** @var \Snog\Movies\Service\Movie\Editor $editor */
		$editor = $this->service('Snog\Movies:Movie\Editor', $movie);

		$input = $this->filter([
			'tmdb_title' => 'str',
			'tmdb_tagline' => 'str',
			'tmdb_genres' => 'str',
			'tmdb_director' => 'str',
			'tmdb_cast' => 'str',
			'tmdb_release' => 'str',
			'tmdb_runtime' => 'uint',
			'tmdb_status' => 'str',
			'tmdb_plot' => 'str'
		]);

		$editor->setTitle($input['tmdb_title']);
		$editor->setTagline($input['tmdb_tagline']);
		$editor->setPlot($input['tmdb_plot']);
		$editor->setGenres($input['tmdb_genres']);
		$editor->setDirector($input['tmdb_director']);
		$editor->setCast($input['tmdb_cast']);
		$editor->setRuntime($input['tmdb_runtime']);
		$editor->setStatus($input['tmdb_status']);
		$editor->setRelease($input['tmdb_release']);

		$url = $this->filter('tmdb_trailer', 'str');
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

		if (!$this->options()->tmdbthreads_force_comments) {
			$editor->setComment($comment);
		} else {
			$editor->setComment('');
		}

		if (\XF::options()->tmdbthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($title);
		}

		$postEditor = $editor->getPostEditor();

		$movie = $editor->getMovie();
		$message = $movie->getPostMessage();

		if (!$this->app->options()->tmdbthreads_force_comments) {
			$message .= $comment;
		}
		$postEditor->setMessage($message);

		$forum = $movie->Thread->Forum;
		if ($forum->canUploadAndManageAttachments()) {
			$postEditor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		return $editor;
	}

	public function actionPoster(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id || (!$visitor->is_moderator && !$visitor->is_admin)) {
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id);
		$movie = $thread->Movie;
		if (!$movie) {
			return $this->notFound();
		}

		if ($this->isPost()) {
			$posterPath = $this->filter('posterpath', 'str');
			$movie->tmdb_image = $posterPath;

			if ($this->options()->tmdbthreads_forum_local) {
				/** @var \Snog\Movies\Service\Movie\Image $imageService */
				$imageService = $this->app->service('Snog\Movies:Movie\Image', $movie);
				if (!$imageService->setImageFromApiPath($posterPath)) {
					return $this->error($imageService->getError());
				}

				$imageService->updateImage();
			}

			$movie->save();

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$newPoster = false;
		$posterPath = '';

		/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$movieInfo = $tmdbClient->getMovie($movie->tmdb_id)->getDetails();
		if ($tmdbClient->hasError()) {
			return $this->error($tmdbClient->getError());
		}

		if (!isset($movieInfo['id'])) {
			return $this->error('Movie Information Not Returned from TMDB.');
		}

		if (isset($movieInfo['poster_path'])) {
			$posterPath = $movieInfo['poster_path'];
		}
		if ($posterPath !== $movie->tmdb_image) {
			$newPoster = true;
		}

		$viewParams = ['movie' => $movie, 'newposter' => $newPoster, 'posterpath' => $posterPath];
		return $this->view('Snog\Movies:Movies', 'snog_movies_new_poster', $viewParams);
	}

	protected function setupPosterUpdate(\Snog\Movies\Entity\Movie $movie)
	{
		/** @var \Snog\Movies\Service\Movie\Editor $editor */
		$editor = $this->service('Snog\Movies:Movie\Editor', $movie);
		$editor->setIsAutomated();

		if (\XF::options()->tmdbthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($title);
		}

		$postEditor = $editor->getPostEditor();

		$movie = $editor->getMovie();
		$message = $movie->getPostMessage();

		if ($movie->comment && !$this->app->options()->tmdbthreads_force_comments) {
			$message .= $movie->comment;
		}
		$postEditor->setMessage($message);

		return $editor;
	}

	public function actionAddInfo(ParameterBag $params)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id || (!$visitor->is_moderator && !$visitor->is_admin)) {
			throw $this->exception($this->noPermission());
		}

		$thread = $this->assertViewableThread($params->thread_id);

		if ($this->isPost()) {
			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$title = $this->filter('tmdb', 'str');
			$changeTitle = $this->filter('changetitle', 'uint');

			if (!$title) {
				return $this->error(\XF::phrase('snog_movies_error_no_movie'));
			}

			/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
			$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');
			$movieId = $tmdbHelper->parseMovieId($title);
			if (!$movieId) {
				return $this->error(\XF::phrase('snog_movies_error_not_valid'));
			}

			/** @var \Snog\Movies\Entity\Movie $exists */
			$exists = $this->finder('Snog\Movies:Movie')->where('tmdb_id', $movieId)->fetchOne();
			$comment = $post->message;

			// MOVIE ALREADY EXISTS
			if (isset($exists->tmdb_id)) {
				return $this->error(\XF::phrase('snog_movies_error_posted'));
			}

			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getMovie($movieId)->getDetails(['casts', 'trailers', 'videos']);
			if ($tmdbClient->hasError()) {
				return $this->error($tmdbClient->getError());
			}

			/** @var \Snog\Movies\Entity\Movie $movie */
			$movie = $this->em()->create('Snog\Movies:Movie');
			$movie->thread_id = $thread->thread_id;
			$movie->setFromApiResponse($apiResponse);

			if (!empty($apiResponse['poster_path'])) {
				/** @var \Snog\Movies\Service\Movie\Image $imageService */
				$imageService = $this->app->service('Snog\Movies:Movie\Image', $movie);
				$imageService->setImageFromApiPath($apiResponse['poster_path']);
				$imageService->updateImage();
			}

			/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
			$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');

			$message = $movie->getPostMessage();
			if (!$this->options()->tmdbthreads_force_comments) {
				$message .= $comment;
			}

			$post->message = $message;
			$post->last_edit_date = 0;

			if ($changeTitle) {
				$thread->title = $movie->getThreadTitle($thread);
				$thread->save();
			}

			$post->save();

			if ($comment && $this->options()->tmdbthreads_force_comments) {
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
				$newpostId = $newPost->getEntityId();

				if ($thread->first_post_id > 0 && $thread->first_post_id <> $post->post_id) {
					$newFirstPost = true;
				}
				if ($thread->first_post_id == $thread->last_post_id) {
					$newLastPost = true;
				}

				if ($newFirstPost) $thread->first_post_id = $newpostId;
				if ($newLastPost) {
					$thread->last_post_date = $thread->post_date;
					$thread->last_post_id = $newpostId;
					$thread->last_post_user_id = $thread->user_id;
					$thread->last_post_username = $thread->username;
				}
				if ($thread->isChanged('first_post_id') || $thread->isChanged('last_post_id')) $thread->save();

				/** @var \XF\Entity\Post[] $postorder */
				$postorder = $this->finder('XF:Post')
					->where('thread_id', $thread->thread_id)
					->order('post_date')
					->fetch();

				$order = 1;
				foreach ($postorder as $changeorder) {
					if ($changeorder->post_id <> $thread->first_post_id) {
						$changeorder->position = $order;
						$changeorder->save();
						$order = $order + 1;
					}
				}
			}

			if (!$this->options()->tmdbthreads_force_comments) {
				$movie->comment = $comment;
			}

			$movie->save(false, false);

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = ['thread' => $thread];
		return $this->view('Snog\Movies:Movies', 'snog_movies_add_info', $viewParams);
	}

	/**
	 * @param $threadId
	 * @param array $extraWith
	 * @return \Snog\Movies\XF\Entity\Thread
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

		/** @var \Snog\Movies\XF\Entity\Thread $thread */
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

	protected function assertViewablePost($postId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
		$extraWith[] = 'Thread';
		$extraWith[] = 'Thread.Forum';
		$extraWith[] = 'Thread.Forum.Node';
		$extraWith[] = 'Thread.Forum.Node.Permissions|' . $visitor->permission_combination_id;

		/** @var \XF\Entity\Post $post */
		$post = $this->em()->find('XF:Post', $postId, $extraWith);
		if (!$post) {
			throw $this->exception($this->notFound(\XF::phrase('requested_post_not_found')));
		}
		if (!$post->canView($error)) {
			throw $this->exception($this->noPermission($error));
		}

		/** @var \XF\ControllerPlugin\Node $nodePlugin */
		$nodePlugin = $this->plugin('XF:Node');
		$nodePlugin->applyNodeContext($post->Thread->Forum->Node);
		return $post;
	}

	/**
	 * @param $id
	 * @param null $with
	 * @return \XF\Mvc\Entity\Entity|\Snog\Movies\Entity\Movie
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertMovieExists($id, $with = null)
	{
		return $this->assertRecordExists('Snog\Movies:Movie', $id, $with);
	}


	// public function actionSync(ParameterBag $params)
	// {
	// 	$thread = $this->assertViewableThread($params->thread_id);

	// 	$typeCreator = \XF::service('Snog\Movies:Thread\TypeData\MovieCreator', $thread);

	// 	$movieCreator = $typeCreator->getMovieCreator();

	// 	// var_dump($thread->Movie->delete());
	// 	// exit;

	// 	$movieId = 299054;
	// 	/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
	// 	$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');
	// 	$movieCreator->setMovieId($tmdbHelper->parseMovieId($movieId));

	// 	$thread->setOption('movieApiResponse', $movieCreator->getMovieApiResponse());

	// 	$movieCreator->save();
	// }
}
