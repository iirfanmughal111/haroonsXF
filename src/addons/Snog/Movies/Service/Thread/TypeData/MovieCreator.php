<?php


namespace Snog\Movies\Service\Thread\TypeData;


use XF\Service\Thread\TypeData\SaverInterface;

class MovieCreator extends \XF\Service\AbstractService implements SaverInterface
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \Snog\Movies\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \Snog\Movies\Service\Movie\Creator
	 */
	protected $movieCreator;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread)
	{
		parent::__construct($app);
		$this->thread = $thread;
		$this->movieCreator = $this->service('Snog\Movies:Movie\Creator', $thread);
		$this->movieCreator->setIsAutomated();
	}

	/**
	 * @return \Snog\Movies\XF\Entity\Thread
	 */
	public function getThread()
	{
		return $this->thread;
	}

	/**
	 * @return \Snog\Movies\Service\Movie\Creator
	 */
	public function getMovieCreator()
	{
		return $this->movieCreator;
	}

	protected function _validate()
	{
		$movieCreator = $this->movieCreator;
		$movieCreator->setComment($this->thread->getOption('movieOriginalMessage'));

		if (!$this->movieCreator->validate($errors))
		{
			return $errors;
		}
		else
		{
			return [];
		}
	}

	protected function _save()
	{
		$movie = $this->movieCreator->save();

		if ($movie && \XF::isAddOnActive('ThemeHouse/Covers'))
		{
			$this->updateCoverImage($movie);
		}

		return $movie;
	}

	public function updateCoverImage(\Snog\Movies\Entity\Movie $movie)
	{
		$backdropSize = $this->app->options()->tmdbthreads_backdropCoverSize;
		if ($backdropSize == 'none')
		{
			return;
		}

		$thread = $this->thread;
		$movieData = $thread->getOption('movieApiResponse');
		if (empty($movieData['backdrop_path']))
		{
			return;
		}

		/** @var \Snog\Movies\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('Snog\Movies:Thread\Cover', $movie);
		$coverService->update($movieData['backdrop_path']);
	}
}