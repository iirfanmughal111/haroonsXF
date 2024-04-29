<?php


namespace Snog\Movies\Service\Movie;


use Snog\Movies\Tmdb\Image;
use XF\Util\File;

class Editor extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \Snog\Movies\Entity\Movie
	 */
	protected $movie;

	/**
	 * @var Preparer
	 */
	protected $moviePreparer;

	/**
	 * @var \Snog\Movies\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \XF\Service\Thread\Editor
	 */
	protected $threadEditor;

	/**
	 * @var \XF\Entity\Post
	 */
	protected $post;

	/**
	 * @var \XF\Service\Post\Editor
	 */
	protected $postEditor;

	protected $performValidations = true;

	public function __construct(\XF\App $app, \Snog\Movies\Entity\Movie $movie)
	{
		parent::__construct($app);
		$this->setMovie($movie);

		if ($movie->Thread)
		{
			$this->setThread($movie->Thread);
			if ($movie->Thread->FirstPost)
			{
				$this->setPost($movie->Thread->FirstPost);
			}
		}

		$this->moviePreparer = $this->service('Snog\Movies:Movie\Preparer', $movie);
	}

	protected function setMovie(\Snog\Movies\Entity\Movie $movie)
	{
		$this->movie = $movie;
	}

	public function getMovie()
	{
		return $this->movie;
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

	public function setFromApiResponse(array $apiResponse)
	{
		$this->movie->setFromApiResponse($apiResponse);
	}

	public function setTitle($title)
	{
		$this->movie->tmdb_title = $title;
	}

	public function setTagline($tagline)
	{
		$this->movie->tmdb_tagline = $tagline;
	}

	public function setPlot($plot)
	{
		$this->movie->tmdb_plot = $plot;
	}

	public function setGenres($genres)
	{
		$this->movie->tmdb_genres = $genres;
	}

	public function setDirector($director)
	{
		$this->movie->tmdb_director = $director;
	}

	public function setCast($cast)
	{
		$this->movie->tmdb_cast = $cast;
	}

	public function setTrailer($trailer)
	{
		$this->movie->tmdb_trailer = $trailer;
	}

	public function setRuntime($runtime)
	{
		$this->movie->tmdb_runtime = $runtime;
	}

	public function setStatus($status)
	{
		$this->movie->tmdb_status = $status;
	}

	public function setRelease($release)
	{
		$this->movie->tmdb_release = $release;
	}

	public function setComment($comment)
	{
		$this->movie->comment = $comment;
	}

	public function setImage($posterPath)
	{
		$this->movie->tmdb_image = $posterPath;
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool) $perform;
	}

	public function setIsAutomated()
	{
		$this->setPerformValidations(false);
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();
		$movie = $this->movie;

		$movie->preSave();
		$errors = $movie->getErrors();

		if ($this->performValidations)
		{
			if ($this->threadEditor && !$this->threadEditor->validate($errors))
			{
				return $errors;
			}

			if ($this->postEditor && !$this->postEditor->validate($errors))
			{
				return $errors;
			}

			if (!empty($movie->tmdb_release))
			{
				$releaseExploded = explode('-', $movie->tmdb_release);
				if (!isset($releaseExploded[0]) || strlen($releaseExploded[0]) !== 4)
				{
					$errors[] = \XF::phrase('snog_movies_error_release_date_format');
				}
			}
		}

		return $errors;
	}

	protected function _save()
	{
		$app = $this->app;
		$db = $this->db();
		$db->beginTransaction();

		$movie = $this->movie;

		if ($this->threadEditor)
		{
			$this->threadEditor->save();
		}

		if ($this->postEditor)
		{
			$this->postEditor->save();
		}

		$movie->save();

		$this->moviePreparer->afterUpdate();

		if (isset($thread->User) && $thread->User->user_id != \XF::visitor()->user_id)
		{
			$app->logger()->logModeratorAction('thread', $thread, 'snog_movies_movie_edit');
		}

		$db->commit();

		return $movie;
	}
}