<?php

namespace nick97\TraktMovies\Service\Movie;

class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \nick97\TraktMovies\Entity\Movie
	 */
	protected $movie;

	/**
	 * @var \nick97\TraktMovies\Entity\Rating
	 */
	protected $rating;

	/**
	 * @var \XF\Entity\User
	 */
	protected $user;

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Movie $movie)
	{
		parent::__construct($app);
		$this->movie = $movie;

		$this->setUser(\XF::visitor());
		$this->rating = $this->setupRating();
	}

	public function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;
	}

	protected function setupRating()
	{
		/** @var \nick97\TraktMovies\Entity\Rating $rating */
		$rating = $this->em()->create('nick97\TraktMovies:Rating');

		$rating->thread_id = $this->movie->thread_id;
		$rating->user_id = $this->user->user_id;

		return $rating;
	}

	public function setRating($rating)
	{
		$this->rating->rating = $rating;
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$rating = $this->rating;
		$this->finalSetup();

		$rating->preSave();
		return $rating->getErrors();
	}

	protected function afterInsert()
	{
	}

	protected function _save()
	{
		$rating = $this->rating;

		$db = $this->db();
		$db->beginTransaction();

		$existing = $this->movie->Ratings[$this->user->user_id] ?? null;
		if ($existing) {
			$existing->delete();
		}

		$rating->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $rating;
	}
}
