<?php

namespace nick97\TraktMovies\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Movies extends AbstractController
{
	public function actionCasts(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktMovie']);
		$movie = $thread->traktMovie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktthreads_castsLimit;

		/** @var \nick97\TraktMovies\Repository\Cast $castRepo */
		$castRepo = \XF::repository('nick97\TraktMovies:Cast');
		$castFinder = $castRepo->findCastForMovie($movie->trakt_id)
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
		return $this->view('nick97\TraktMovies:Movies\Casts', 'trakt_movies_casts', $viewParams);
	}

	public function actionCrews(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktMovie']);
		$movie = $thread->traktMovie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktthreads_crewLimit;

		/** @var \nick97\TraktMovies\Repository\Crew $crewRepo */
		$crewRepo = \XF::repository('nick97\TraktMovies:Crew');
		$crewFinder = $crewRepo->findCrewForMovie($movie->trakt_id)
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
		return $this->view('nick97\TraktMovies:Movies\Crew', 'trakt_movies_crews', $viewParams);
	}

	public function actionVideos(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id, ['traktMovie']);
		$movie = $thread->traktMovie;
		if (!$movie) {
			return $this->notFound();
		}

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->traktthreads_videoLimit;

		/** @var \nick97\TraktMovies\Repository\Video $videoRepo */
		$videoRepo = \XF::repository('nick97\TraktMovies:Video');
		$videoFinder = $videoRepo->findVideosForList()
			->forMovie($movie->trakt_id)
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
		return $this->view('nick97\TraktMovies:Movies\Videos', 'trakt_movies_videos', $viewParams);
	}

	public function actionRate(ParameterBag $params)
	{
		$visitor = \XF::visitor();

		$thread = $this->assertViewableThread($params->thread_id, ['traktMovie']);
		$movie = $thread->traktMovie;

		if (!$movie) {
			return $this->notFound();
		}

		/** @var \nick97\TraktMovies\Entity\Rating $userRating */
		$userRating = $this->em()->create('nick97\TraktMovies:Rating');
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
		return $this->view('nick97\TraktMovies:Movies\Movie', 'trakt_movies_rate', $viewParams);
	}

	protected function setupMovieRate(\nick97\TraktMovies\Entity\Movie $movie)
	{
		/** @var \nick97\TraktMovies\Service\Movie\Rate $rater */
		$rater = $this->service('nick97\TraktMovies:Movie\Rate', $movie);
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
		return $this->view('XF:Post\Edit', 'trakt_movies_movie_edit', $viewParams);
	}

	protected function setupMovieEdit(\nick97\TraktMovies\Entity\Movie $movie)
	{
		/** @var \nick97\TraktMovies\Service\Movie\Editor $editor */
		$editor = $this->service('nick97\TraktMovies:Movie\Editor', $movie);

		$input = $this->filter([
			'trakt_title' => 'str',
			'trakt_tagline' => 'str',
			'trakt_genres' => 'str',
			'trakt_director' => 'str',
			'trakt_cast' => 'str',
			'trakt_release' => 'str',
			'trakt_runtime' => 'uint',
			'trakt_status' => 'str',
			'trakt_plot' => 'str'
		]);

		$editor->setTitle($input['trakt_title']);
		$editor->setTagline($input['trakt_tagline']);
		$editor->setPlot($input['trakt_plot']);
		$editor->setGenres($input['trakt_genres']);
		$editor->setDirector($input['trakt_director']);
		$editor->setCast($input['trakt_cast']);
		$editor->setRuntime($input['trakt_runtime']);
		$editor->setStatus($input['trakt_status']);
		$editor->setRelease($input['trakt_release']);

		$url = $this->filter('trakt_trailer', 'str');
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

		if (!$this->options()->traktthreads_force_comments) {
			$editor->setComment($comment);
		} else {
			$editor->setComment('');
		}

		if (\XF::options()->traktthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($title);
		}

		$postEditor = $editor->getPostEditor();

		$movie = $editor->getMovie();
		$message = $movie->getPostMessage();

		if (!$this->app->options()->traktthreads_force_comments) {
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
		$movie = $thread->traktMovie;
		if (!$movie) {
			return $this->notFound();
		}

		if ($this->isPost()) {
			$posterPath = $this->filter('posterpath', 'str');
			$movie->trakt_image = $posterPath;

			if ($this->options()->traktthreads_forum_local) {
				/** @var \nick97\TraktMovies\Service\Movie\Image $imageService */
				$imageService = $this->app->service('nick97\TraktMovies:Movie\Image', $movie);
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

		/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$movieInfo = $traktClient->getMovie($movie->trakt_id)->getDetails();
		if ($traktClient->hasError()) {
			return $this->error($traktClient->getError());
		}

		if (!isset($movieInfo['id'])) {
			return $this->error('Movie Information Not Returned from Trakt.');
		}

		if (isset($movieInfo['poster_path'])) {
			$posterPath = $movieInfo['poster_path'];
		}
		if ($posterPath !== $movie->trakt_image) {
			$newPoster = true;
		}

		$viewParams = ['movie' => $movie, 'newposter' => $newPoster, 'posterpath' => $posterPath];
		return $this->view('nick97\TraktMovies:Movies', 'trakt_movies_new_poster', $viewParams);
	}

	protected function setupPosterUpdate(\nick97\TraktMovies\Entity\Movie $movie)
	{
		/** @var \nick97\TraktMovies\Service\Movie\Editor $editor */
		$editor = $this->service('nick97\TraktMovies:Movie\Editor', $movie);
		$editor->setIsAutomated();

		if (\XF::options()->traktthreads_syncTitle) {
			$title = $movie->getExpectedThreadTitle();
			$threadEditor = $editor->getThreadEditor();
			$threadEditor->setTitle($title);
		}

		$postEditor = $editor->getPostEditor();

		$movie = $editor->getMovie();
		$message = $movie->getPostMessage();

		if ($movie->comment && !$this->app->options()->traktthreads_force_comments) {
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

			$clientKey = \XF::options()->traktMovieThreads_apikey;

			if (!$clientKey) {

				throw $this->exception(
					$this->notFound(\XF::phrase("nick97_movie_trakt_api_key_not_found"))
				);
			}

			$post = $this->assertViewablePost($thread->first_post_id, ['Thread.Prefix']);
			$title = $this->filter('trakt', 'str');
			$changeTitle = $this->filter('changetitle', 'uint');

			if (!$title) {
				return $this->error(\XF::phrase('trakt_movies_error_no_movie'));
			}

			/** @var \nick97\TraktMovies\Helper\Trakt $traktHelper */
			$traktHelper = \XF::helper('nick97\TraktMovies:Trakt');
			$movieId = $traktHelper->parseMovieId($title);
			if (!$movieId) {
				return $this->error(\XF::phrase('trakt_movies_error_not_valid'));
			}

			/** @var \nick97\TraktMovies\Entity\Movie $exists */
			$exists = $this->finder('nick97\TraktMovies:Movie')->where('trakt_id', $movieId)->fetchOne();
			$comment = $post->message;

			// MOVIE ALREADY EXISTS
			if (isset($exists->trakt_id)) {
				return $this->error(\XF::phrase('trakt_movies_error_posted'));
			}

			/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$apiResponse = $traktClient->getMovie($movieId)->getDetails(['casts', 'trailers', 'videos']);
			if ($traktClient->hasError()) {
				return $this->error($traktClient->getError());
			}

			/** @var \nick97\TraktMovies\Entity\Movie $movie */
			$movie = $this->em()->create('nick97\TraktMovies:Movie');
			$movie->thread_id = $thread->thread_id;
			$movie->setFromApiResponse($apiResponse);

			if (!empty($apiResponse['poster_path'])) {
				/** @var \nick97\TraktMovies\Service\Movie\Image $imageService */
				$imageService = $this->app->service('nick97\TraktMovies:Movie\Image', $movie);
				$imageService->setImageFromApiPath($apiResponse['poster_path']);
				$imageService->updateImage();
			}

			/** @var \nick97\TraktMovies\Helper\Trakt $traktHelper */
			$traktHelper = \XF::helper('nick97\TraktMovies:Trakt');

			$message = $movie->getPostMessage();
			if (!$this->options()->traktthreads_force_comments) {
				$message .= $comment;
			}

			$post->message = $message;
			$post->last_edit_date = 0;

			if ($changeTitle) {
				$thread->title = $movie->getThreadTitle($thread);
				$thread->save();
			}

			$post->save();

			if ($comment && $this->options()->traktthreads_force_comments) {
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

			if (!$this->options()->traktthreads_force_comments) {
				$movie->comment = $comment;
			}

			$movie->save(false, false);

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = ['thread' => $thread];
		return $this->view('nick97\TraktMovies:Movies', 'trakt_movies_add_info', $viewParams);
	}

	/**
	 * @param $threadId
	 * @param array $extraWith
	 * @return \nick97\TraktMovies\XF\Entity\Thread
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

		/** @var \nick97\TraktMovies\XF\Entity\Thread $thread */
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
	 * @return \XF\Mvc\Entity\Entity|\nick97\TraktMovies\Entity\Movie
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function assertMovieExists($id, $with = null)
	{
		return $this->assertRecordExists('nick97\TraktMovies:Movie', $id, $with);
	}

	// public function actionSync(ParameterBag $params)
	// {
	// 	$visitor = \XF::visitor();
	// 	if (!$visitor->user_id) {
	// 		return $this->noPermission();
	// 	}

	// 	$thread = $this->assertViewableThread($params->thread_id);

	// 	if ($this->isPost()) {
	// 		$typeCreator = \XF::service('nick97\TraktMovies:Thread\TypeData\MovieCreator', $thread, 22525);


	// 		$movieCreator = $typeCreator->getMovieCreator();

	// 		$movieId = $thread->traktMovie->trakt_id;

	// 		$threadId = $thread->thread_id;



	// 		\xf::db()->delete('nick97_trakt_movies_thread', 'thread_id = ?', $thread->thread_id);

	// 		\xf::db()->delete('nick97_trakt_movies_crew', 'trakt_id = ?', $movieId);
	// 		\xf::db()->delete('nick97_trakt_movies_cast', 'trakt_id = ?', $movieId);


	// 		$casts = $this->finder('nick97\TraktMovies:Cast')->where('trakt_id', $movieId)->fetch();

	// 		if (count($casts)) {

	// 			$this->deleteMovies($casts);
	// 		}

	// 		$Crews = $this->finder('nick97\TraktMovies:Crew')->where('trakt_id', $movieId)->fetch();

	// 		if (count($Crews)) {

	// 			$this->deleteMovies($Crews);
	// 		}
	// 		$Videos = $this->finder('nick97\TraktMovies:Video')->where('trakt_id', $movieId)->fetch();

	// 		if (count($Videos)) {

	// 			$this->deleteMovies($Videos);
	// 		}

	// 		$Ratings = $this->finder('nick97\TraktMovies:Rating')->where('thread_id', $thread->thread_id)->fetch();

	// 		if (count($Ratings)) {

	// 			$this->deleteMovies($Ratings);
	// 		}

	// 		// $movie = $thread->traktMovie;
	// 		// $message = $movie->getPostMessage();

	// 		// $comment = $thread->FirstPost->message;

	// 		// if (!\xf::options()->traktthreads_force_comments) {
	// 		// 	$message .= $comment;
	// 		// }



	// 		// $thread->setOption('movieOriginalMessage', $comment);

	// 		$movieCreator->setMovieId($movieId);

	// 		$thread->setOption('movieApiResponse', $movieCreator->getMovieApiResponse());

	// 		$movieCreator->save();

	// 		$movie = $this->finder('nick97\TraktMovies:Movie')->where('thread_id', 22525)->fetchOne();
	// 		$movie->fastUpdate('thread_id', $threadId);
	// 		$thread->fastUpdate('title', $thread->traktMovie->trakt_title);

	// 		return $this->redirect($this->buildLink('threads', $thread));
	// 	} else {
	// 		$viewParams = [
	// 			'thread' => $thread,
	// 		];
	// 		return $this->view('XF:Thread\WatchList', 'nick97_trakt_watch_list_sync_confirm', $viewParams);
	// 	}
	// }

	public function deleteMovies($datas)
	{

		foreach ($datas as $data) {

			$data->delete();
		}
	}
}
