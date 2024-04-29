<?php

namespace nick97\TraktTV\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Util\File;

class Forum extends XFCP_Forum
{
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionPostThread(ParameterBag $params)
	{
		if (!$this->isPost()) {
			return parent::actionPostThread($params);
		}

		if (!$params->node_id && !$params->node_name) {
			return parent::actionPostThread($params);
		}

		$visitor = \XF::visitor();

		/** @var \nick97\TraktTV\XF\Entity\Forum $forum */
		$forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . $visitor->user_id]);

		if ($forum->isThreadTypeCreatable('trakt_tv')) {
			if (!\XF::visitor()->hasPermission('trakt_tvthreads_interface', 'use_trakt_tv')) {
				throw $this->exception($this->noPermission());
			}
		}

		if (!$forum->isThreadTypeCreatable('trakt_tv')) {
			return parent::actionPostThread($params);
		}

		$title = $this->filter('trakt_tv_tv_id', 'str');

		if (!$forum->canCreateThread($error)) {
			return $this->noPermission($error);
		}

		if ($visitor->isShownCaptcha() && !$this->app->captcha()->isValid()) {
			return $this->error(\XF::phrase('did_not_complete_the_captcha_verification_properly'));
		}

		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$comment = $editorPlugin->fromInput('message');

		$clientKey = \XF::options()->traktTvThreads_clientkey;

		if (!$clientKey) {
			throw $this->exception(
				$this->notFound(\XF::phrase("nick97_tv_trakt_api_key_not_found"))
			);
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Show $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktTV:Trakt\Show');
		$showId = $traktHelper->parseShowId($title);

		if (!$this->options()->traktTvThreads_multiple) {
			/** @var \nick97\TraktTV\Entity\TV $exists */
			$exists = $this->finder('nick97\TraktTV:TV')->where('tv_id', $showId)->fetchOne();

			// Show already exists - if comments made post to existing thread
			if (isset($exists->tv_id) && $comment) {
				/** @var \XF\Entity\Thread $thread */
				$thread = $exists->getRelationOrDefault('Thread');

				/** @var \XF\Service\Thread\Replier $replier */
				$replier = $this->service('XF:Thread\Replier', $thread);
				$replier->setMessage($comment);

				if ($forum->canUploadAndManageAttachments()) {
					$replier->setAttachmentHash($this->filter('attachment_hash', 'str'));
				}

				$post = $replier->save();

				/** @var \XF\ControllerPlugin\Thread $threadPlugin */
				$threadPlugin = $this->plugin('XF:Thread');
				return $this->redirect($threadPlugin->getPostLink($post), 'Your comments have been posted in the existing thread for the TV show');
			}

			// Show already exists - no comments - send to existing thread
			if (isset($exists->tv_id)) {
				/** @var \XF\Entity\Thread $thread */
				$thread = $exists->getRelationOrDefault('Thread');
				return $this->redirect($this->buildLink('threads', $thread));
			}
		}

		return parent::actionPostThread($params);
	}

	public function actionAddTVForum(ParameterBag $params)
	{
		/** @var \XF\Entity\Node $node */
		$node = $this->assertRecordExists('XF:Node', $params->node_id);

		if ($this->isPost()) {
			$title = $this->filter('tvlink', 'str');

			$clientKey = \XF::options()->traktTvThreads_clientkey;

			if (!$clientKey) {
				throw $this->exception(
					$this->notFound(\XF::phrase("nick97_tv_trakt_api_key_not_found"))
				);
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Show $traktHelper */
			$traktHelper = \XF::helper('nick97\TraktTV:Trakt\Show');
			$showId = $traktHelper->parseShowId($title);
			if (!$showId) {
				return $this->error(\XF::phrase('trakt_tv_error_id_not_valid'));
			}

			/** @var \nick97\TraktTV\Entity\TVForum $exists */
			$exists = $this->finder('nick97\TraktTV:TVForum')->where('tv_id', $showId)->fetchOne();
			if ($exists) {
				return $this->error(\XF::phrase('trakt_tv_error_forum_exists'));
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = $this->helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$showInfo = $traktClient->getTv($showId)->getDetails(['credits']);

			if ($traktClient->hasError()) {
				return $this->error($traktClient->getError());
			}

			$creator = $this->setupTVForumCreate($node, $showInfo);

			if (!$creator->validate($errors)) {
				return $this->error($errors);
			}
			$newNode = $creator->save();

			return $this->redirect($this->buildLink('categories', $newNode));
		}

		$viewParams = [
			'node' => $node
		];
		return $this->view('XF:Forum\AddTVForum', 'trakt_tv_new_forum', $viewParams);
	}

	protected function setupTVForumCreate(\XF\Entity\Node $parentNode, $showInfo)
	{
		$tvTitle = html_entity_decode($showInfo['name']);

		/** @var \nick97\TraktTV\Service\TVForum\Create $creator */
		$creator = $this->service('nick97\TraktTV:TVForum\Create', $parentNode, $showInfo['id']);
		$creator->setShowTitle($tvTitle);

		/** @var \nick97\TraktTV\Helper\Trakt\Show $tvHelper */
		$tvHelper = $this->app->helper('nick97\TraktTV:Trakt\Show');
		$creator->setTvGenres($tvHelper->getGenresList($showInfo));
		$creator->setTvDirectors($tvHelper->getDirectorsList($showInfo));
		$creator->setTvCast($tvHelper->getCastList($showInfo));

		if (!empty($showInfo['poster_path'])) {
			$creator->setTvImage($showInfo['poster_path']);
		}

		$creator->setTvRelease($showInfo['first_air_date']);
		$creator->setTvPlot($showInfo['overview']);

		return $creator;
	}

	protected function finalizeTraktTVForumCreate(\nick97\TraktTV\Service\TVForum\Create $creator)
	{
	}

	public function actionNewSeason(ParameterBag $params)
	{
		/** @var \XF\Entity\Node $node */
		$node = $this->assertRecordExists('XF:Node', $params->node_id);

		if ($this->isPost()) {
			$season = $this->filter('season', 'uint');

			/** @var \nick97\TraktTV\Entity\TVForum $show */
			$show = $this->assertRecordExists('nick97\TraktTV:TVForum', $node->node_id);
			$showId = $show->tv_id;

			/** @var \nick97\TraktTV\Entity\TVForum $exists */
			$exists = $this->em()->findOne('nick97\TraktTV:TVForum', ['tv_id' => $showId, 'tv_season' => $season]);
			if ($exists) {
				return $this->error(\XF::phrase('trakt_tv_error_forum_exists'));
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = $this->helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$seasonInfo = $traktClient->getTv($showId)->getSeason($season)->getDetails();

			if ($traktClient->hasError()) {
				return $this->error($traktClient->getError());
			}

			$creator = $this->setupTraktTVSeasonCreate($node, $show, $season, $seasonInfo);
			if (!$creator->validate($errors)) {
				return $this->error($errors);
			}
			$newNode = $creator->save();
			$this->finalizeTraktTVSeasonCreate($creator);

			return $this->redirect($this->buildLink('forums', $newNode));
		}

		$viewParams = [
			'node' => $node
		];
		return $this->view('nick97\TraktTV:TV', 'trakt_tv_new_season', $viewParams);
	}

	protected function setupTraktTVSeasonCreate(\XF\Entity\Node $parentNode, \nick97\TraktTV\Entity\TVForum $show, $season, $seasonInfo)
	{
		$title = $show->tv_title;
		$title .= ": " . html_entity_decode($seasonInfo['name']);

		/** @var \nick97\TraktTV\Service\TVForum\Create $creator */
		$creator = $this->service('nick97\TraktTV:TVForum\Create', $parentNode, $show->tv_id);

		$creator->setSeason($season);
		$creator->setNewNodeDisplayOrder($season * 100);

		$creator->setShowTitle($title);

		/** @var \nick97\TraktTV\Helper\Trakt\Show $tvHelper */
		$tvHelper = $this->app->helper('nick97\TraktTV:Trakt\Show');
		$creator->setTvGenres($tvHelper->getGenresList($seasonInfo));
		$creator->setTvDirectors($tvHelper->getDirectorsList($seasonInfo));
		$creator->setTvCast($tvHelper->getCastList($seasonInfo));

		if (!empty($seasonInfo['poster_path'])) {
			$creator->setTvImage($seasonInfo['poster_path']);
		}

		$creator->setTvRelease($seasonInfo['air_date']);
		$creator->setTvPlot($seasonInfo['overview']);

		return $creator;
	}

	protected function finalizeTraktTVSeasonCreate(\nick97\TraktTV\Service\TVForum\Create $creator)
	{
	}

	public function actionNewEpisode(ParameterBag $params)
	{
		if (!$this->isPost()) {
			return parent::actionPostThread($params);
		}

		$episode = $this->filter('trakt_tv_tv_id', 'uint');
		if (!is_numeric($episode) || $episode <= 0) {
			return $this->error(\XF::phrase('trakt_tv_error_episode_number'));
		}

		$forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . \XF::visitor()->user_id]);
		if (!$forum->canCreateThread($error)) {
			return $this->noPermission($error);
		}

		$switches = $this->filter(['inline-mode' => 'bool', 'more-options' => 'bool']);
		$newThreadTitle = '';

		/** @var \nick97\TraktTV\XF\Entity\Node $show */
		$show = $this->finder('XF:Node')->where('node_id', $params->node_id)->fetchOne();

		/** @var \nick97\TraktTV\XF\Entity\Node $parent */
		$parent = $this->finder('XF:Node')->where('node_id', $show->TVForum->tv_parent)->fetchOne();

		$tvShow = $show->TVForum->tv_parent_id;
		$tvSeason = $show->TVForum->tv_season;

		if (!$this->options()->traktTvThreads_episode_exclude) {
			$newThreadTitle = $parent->TVForum->tv_title;
		}

		/** @var \XF\ControllerPlugin\Editor $editorPlugin */
		$editorPlugin = $this->plugin('XF:Editor');
		$comment = $editorPlugin->fromInput('message');

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$episodeInfo = $traktClient->getTv($tvShow)
			->getSeason($tvSeason)
			->getEpisode($episode)
			->getDetails(['credits']);

		if ($traktClient->hasError()) {
			return $this->error($traktClient->getError());
		}

		if (!$this->options()->traktTvThreads_episode_exclude) {
			$newThreadTitle .= ': ';
		}

		$newThreadTitle .= 'S' . str_pad($episodeInfo['season_number'], 2, '0', STR_PAD_LEFT);
		$newThreadTitle .= 'E' . str_pad($episodeInfo['episode_number'], 2, '0', STR_PAD_LEFT);
		$newThreadTitle .= " " . html_entity_decode($episodeInfo['name']);

		// DOWNLOAD EPISODE IMAGE
		if ($episodeInfo['still_path'] > '') {
			$tempDir = FILE::getTempDir();
			$path = 'data://tv/EpisodePosters' . $episodeInfo['still_path'];
			$tempPath = $tempDir . $episodeInfo['still_path'];
			$this->getTvImage($episodeInfo['still_path'], 'w300', $path, $tempPath);
		}

		$messageInfo = "[B]" . $parent->TVForum->tv_title . "[/B]" . "\r\n";
		$messageInfo .= '[IMG]' . $this->getEpisodeImageUrl(($episodeInfo['still_path'] ?: '/no-poster.jpg')) . '[/IMG]' . "\r\n";

		$guest = '';
		$permStars = '';
		if (!empty($episodeInfo['credits']['guest_stars'])) {
			foreach ($episodeInfo['credits']['cast'] as $cast) {
				if ($permStars) $permStars .= ', ';
				$permStars .= $cast['name'];
			}

			$checkStars = explode(',', $permStars);
			foreach ($episodeInfo['credits']['guest_stars'] as $guestStar) {
				if (!in_array($guestStar['name'], $checkStars)) {
					if ($guest) $guest .= ', ';
					$guest .= $guestStar['name'];
				}
			}
		}

		$messageInfo .= "[B]" . $episodeInfo['name'] . "[/B]" . "\r\n";
		$messageInfo .= "[B]" . \XF::phrase('trakt_tv_season') . ":[/B] " . $episodeInfo['season_number'] . "\r\n";
		$messageInfo .= "[B]" . \XF::phrase('trakt_tv_episode') . ":[/B] " . $episodeInfo['episode_number'] . "\r\n";
		$messageInfo .= "[B]" . \XF::phrase('trakt_tv_air_date') . ":[/B] " . $episodeInfo['air_date'] . "\r\n\r\n";
		if (!empty($guest)) {
			$messageInfo .= "[B]" . \XF::phrase('trakt_tv_guest_stars') . ":[/B] " . $guest . "\r\n\r\n";
		}
		$messageInfo .= $episodeInfo['overview'] . "\r\n";
		$message = $messageInfo . "\r\n\r\n";
		$message .= $comment;

		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setContent($newThreadTitle, $message);

		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId && $forum->isPrefixUsable($prefixId)) {
			$creator->setPrefix($prefixId);
		}
		if ($forum->canEditTags()) {
			$creator->setTags($this->filter('tags', 'str'));
		}

		$setOptions = $this->filter('_xfSet', 'array-bool');

		if ($setOptions) {
			/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
			$thread = $creator->getThread();

			if (isset($setOptions['discussion_open']) && $thread->canLockUnlock()) {
				$creator->setDiscussionOpen($this->filter('discussion_open', 'bool'));
			}
			if (isset($setOptions['sticky']) && $thread->canStickUnstick()) {
				$creator->setSticky($this->filter('sticky', 'bool'));
			}
		}

		$customFields = $this->filter('custom_fields', 'array');
		$creator->setCustomFields($customFields);
		$creator->checkForSpam();

		/** @var \XF\Validator\Username $errors */
		if (!$creator->validate($errors)) {
			return $this->error($errors);
		}

		$this->assertNotFlooding('post');

		/** @var \nick97\TraktTV\XF\Entity\Thread $thread */
		$thread = $creator->save();

		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->em()->create('nick97\TraktTV:TV');
		$tv->thread_id = $thread->thread_id;
		$tv->tv_id = $parent->TVForum->tv_id;
		$tv->tv_title = $parent->TVForum->tv_title;
		$tv->tv_plot = $episodeInfo['overview'];
		$tv->tv_season = $episodeInfo['season_number'];
		$tv->tv_episode = $episodeInfo['episode_number'];
		if (!empty($guest)) {
			$tv->tv_cast = $guest;
		}
		$tv->tv_release = $episodeInfo['air_date'];
		if ($comment) {
			$tv->comment = $comment;
		}
		$tv->save(false, false);

		/** @var \nick97\TraktTV\Entity\TVPost $episodePost */
		$episodePost = $this->em()->create('nick97\TraktTV:TVPost');
		$episodePost->post_id = $thread->first_post_id;
		$episodePost->tv_id = $parent->TVForum->tv_id;
		$episodePost->tv_title = $episodeInfo['name'];
		$episodePost->tv_plot = $episodeInfo['overview'];
		$episodePost->tv_image = $episodeInfo['still_path'];
		$episodePost->tv_season = $episodeInfo['season_number'];
		$episodePost->tv_episode = $episodeInfo['episode_number'];
		if (!empty($guest)) {
			$episodePost->tv_guest = $guest;
		}
		$episodePost->tv_aired = $episodeInfo['air_date'];
		if ($comment) {
			$episodePost->message = $comment;
		}
		$episodePost->save(false, false);

		// MOVE EPISODE IMAGE TO POST ID + IMAGE NAME
		if ($episodeInfo['still_path'] > '') {
			$imageName = $thread->first_post_id . '-' . str_ireplace('/', '', $episodeInfo['still_path']);

			$tempPath = File::copyAbstractedPathToTempFile('data://tv/EpisodePosters' . $episodeInfo['still_path']);
			$path = 'data://tv/EpisodePosters/' . $imageName;
			File::copyFileToAbstractedPath($tempPath, $path);
			unlink($tempPath);

			$path = sprintf('data://tv/EpisodePosters%s', $episodeInfo['still_path']);
			File::deleteFromAbstractedPath($path);

			$replaceMessage = '[IMG]' . $this->getEpisodeImageUrl(($episodeInfo['still_path'] ?: '/no-poster.jpg')) . '[/IMG]' . "\r\n";
			$withReplacement = "[img]" . $episodePost->getEpisodeImageUrl(($episodeInfo['still_path'] ? '' : '/no-poster.jpg')) . "[/img]" . "\r\n";

			if ($episodeInfo['still_path']) {
				$post = $thread->FirstPost;
				$post->message = str_ireplace($replaceMessage, $withReplacement, $post->message);
				$post->save();
			}
		}

		// MOVED HERE FOR COMPATIBILITY ISSUES WITH XenPorta REDIRECTING IN THE FUNCTION

		$this->finalizeThreadCreate($creator);

		if ($switches['inline-mode']) {
			$viewParams = ['thread' => $thread, 'forum' => $forum];
			return $this->view('XF:Forum\ThreadItem', 'thread_list_item', $viewParams);
		} else if (!$thread->canView()) {
			return $this->redirect($this->buildLink('forums', $forum, ['pending_approval' => 1]));
		}

		return $this->redirect($this->buildLink('threads', $thread));
	}

	protected function getForumViewExtraWith()
	{
		$extraWith = parent::getForumViewExtraWith();
		$extraWith[] = 'TVForum';

		return $extraWith;
	}

	protected function getTvImage($srcPath, $size, $localPath, $tempPath)
	{
		$traktApi = new \nick97\TraktTV\Trakt\Image();
		$poster = $traktApi->getImage($srcPath, $size);

		if (file_exists($tempPath)) {
			unlink($tempPath);
		}
		file_put_contents($tempPath, $poster);
		File::copyFileToAbstractedPath($tempPath, $localPath);
		unlink($tempPath);
	}

	public function getEpisodeImageUrl($posterpath, $canonical = true)
	{
		return $this->app->applyExternalDataUrl("tv/EpisodePosters{$posterpath}", $canonical);
	}
}
