<?php

namespace Snog\TV\Service\TV;


use XF\Service\ValidateAndSavableTrait;

class Editor extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var \Snog\TV\Entity\TV
	 */
	protected $tv;

	/**
	 * @var Preparer
	 */
	protected $tvPreparer;

	/**
	 * @var \Snog\TV\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \XF\Service\Thread\Editor
	 */
	protected $threadEditor;

	/**
	 * @var \Snog\TV\XF\Entity\Post
	 */
	protected $post;

	/**
	 * @var \XF\Service\Post\Editor
	 */
	protected $postEditor;

	public function __construct(\XF\App $app, \Snog\TV\Entity\TV $tv)
	{
		parent::__construct($app);
		$this->tv = $tv;
		if ($tv->Thread)
		{
			$this->setThread($tv->Thread);
			if ($tv->Thread->FirstPost)
			{
				$this->setPost($tv->Thread->FirstPost);
			}
		}

		$this->tvPreparer = $this->service('Snog\TV:TV\Preparer', $tv);
	}

	public function setThread(\XF\Entity\Thread $thread)
	{
		$this->thread = $thread;
		$this->threadEditor = $this->setupThreadEdit($thread);
	}

	public function getThread()
	{
		return $this->thread;
	}

	protected function setupThreadEdit(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Service\Thread\Editor $editor */
		$editor = $this->service('XF:Thread\Editor', $thread);
		$editor->setPerformValidations(false);

		return $editor;
	}

	public function getThreadEditor()
	{
		return $this->threadEditor;
	}

	public function setPost(\XF\Entity\Post $post)
	{
		$this->post = $post;
		$this->postEditor = $this->setupPostEdit($post);
	}

	public function getPost()
	{
		return $this->post;
	}

	protected function setupPostEdit(\XF\Entity\Post $post)
	{
		/** @var \XF\Service\Post\Editor $editor */
		$editor = $this->service('XF:Post\Editor', $post);
		$editor->setIsAutomated();
		$editor->logEdit(false);

		return $editor;
	}

	public function getPostEditor()
	{
		return $this->postEditor;
	}

	public function getTv()
	{
		return $this->tv;
	}

	public function setFromApiResponse(array $apiResponse)
	{
		$this->tv->setFromApiResponse($apiResponse);
	}

	public function setTitle($title)
	{
		$this->tv->tv_title = $title;
	}

	public function setPlot($plot)
	{
		$this->tv->tv_plot = $plot;
	}

	public function setGenres($genres)
	{
		$this->tv->tv_genres = $genres;
	}

	public function setDirector($director)
	{
		$this->tv->tv_director = $director;
	}

	public function setCast($cast)
	{
		$this->tv->tv_cast = $cast;
	}

	public function setTrailer($trailer)
	{
		$this->tv->tv_trailer = $trailer;
	}

	public function setFirstAirDate($firstAirDate)
	{
		$this->tv->first_air_date = $firstAirDate;
	}

	public function setLastAirDate($lastAirDate)
	{
		$this->tv->last_air_date = $lastAirDate;
	}

	public function setStatus($status)
	{
		$this->tv->status = $status;
	}

	public function setComment($comment)
	{
		$this->tv->comment = $comment;
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		if (!$this->threadEditor->validate($errors))
		{
			return $errors;
		}

		if (!$this->postEditor->validate($errors))
		{
			return $errors;
		}

		$this->tv->preSave();
		return $this->tv->getErrors();
	}

	protected function _save()
	{
		$tv = $this->tv;

		if ($this->threadEditor)
		{
			$this->threadEditor->save();
		}

		if ($this->postEditor)
		{
			$this->postEditor->save();
		}

		$tv->save();

		$this->tvPreparer->afterUpdate();

		return $tv;
	}
}