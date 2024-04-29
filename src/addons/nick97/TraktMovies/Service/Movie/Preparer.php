<?php

namespace nick97\TraktMovies\Service\Movie;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktMovies\Entity\Movie
	 */
	protected $movie;

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Movie $movie)
	{
		parent::__construct($app);
		$this->movie = $movie;
	}

	public function getMovie()
	{
		return $this->movie;
	}

	public function afterInsert()
	{
	}

	public function afterUpdate()
	{
	}
}
