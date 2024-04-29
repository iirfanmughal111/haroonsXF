<?php

namespace nick97\TraktTV\Service\TVPost;

use nick97\TraktTV\Entity\TVPost;
use XF\Service\ValidateAndSavableTrait;

class Editor extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var TVPost
	 */
	protected $tvPost;

	/**
	 * @var \XF\Service\Post\Editor
	 */
	protected $postEditor;

	public function __construct(\XF\App $app, TVPost $tvPost)
	{
		parent::__construct($app);

		$this->tvPost = $tvPost;

		$this->postEditor = $this->setupPostEditor();
	}

	protected function setupPostEditor()
	{
		/** @var \XF\Service\Post\Editor $editor */
		$editor = $this->service('XF:Post\Editor', $this->tvPost->Post);
		$editor->setIsAutomated();
		$editor->logEdit(false);

		return $editor;
	}

	public function setTitle($title)
	{
		$this->tvPost->tv_title = $title;
	}

	public function setSeason($season)
	{
		$this->tvPost->tv_season = $season;
	}

	public function setEpisode($episode)
	{
		$this->tvPost->tv_episode = $episode;
	}

	public function setAirDate($airDate)
	{
		$this->tvPost->tv_aired = $airDate;

		$releaseExploded = explode('-', $airDate);
		if (!isset($releaseExploded[0]) || strlen($releaseExploded[0]) !== 4) {
			$this->tvPost->error(\XF::phrase('trakt_tv_error_aired_date'));
		}
	}

	public function setGuests($guests)
	{
		$this->tvPost->tv_guest = $guests;
	}

	public function setPlot($plot)
	{
		$this->tvPost->tv_plot = $plot;
	}

	public function setMessage($message)
	{
		$this->tvPost->message = $message;

		$postEditor = $this->postEditor;
		$postEditor->setMessage($this->tvPost->getPostMessage());
	}

	public function getPostEditor()
	{
		return $this->postEditor;
	}

	protected function _validate()
	{
		$tvPost = $this->tvPost;

		if (!$this->postEditor->validate($errors)) {
			return $errors;
		}

		$tvPost->preSave();
		return $tvPost->getErrors();
	}

	protected function _save()
	{
		$tvPost = $this->tvPost;

		$db = $this->db();
		$db->beginTransaction();

		$tvPost->save(true, false);

		$post = $tvPost->Post;
		$thread = $post->Thread;
		$tvForum = $thread->traktTV->Thread->Forum->TVForum;

		/** @var \nick97\TraktTV\Helper\TVPost $postHelper */
		$postHelper = \XF::helper('nick97\TraktTV:TVPost');

		$newTitle = $postHelper->getThreadTitle($thread, $tvPost->toArray(), $tvForum);
		if ($newTitle !== $thread->title) {
			$thread->title = $newTitle;
			$thread->save();
		}

		$postEditor = $this->postEditor;

		$postEditor->save();

		$db->commit();

		return $tvPost;
	}
}
