<?php

namespace Snog\Movies\Service\Movie;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\Movies\Entity\Movie
	 */
	protected $movie;

	public function __construct(\XF\App $app, \Snog\Movies\Entity\Movie $movie)
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