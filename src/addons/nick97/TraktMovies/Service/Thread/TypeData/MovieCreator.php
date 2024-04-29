<?php


namespace nick97\TraktMovies\Service\Thread\TypeData;


use XF\Service\Thread\TypeData\SaverInterface;

class MovieCreator extends \XF\Service\AbstractService implements SaverInterface
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var \nick97\TraktMovies\XF\Entity\Thread
	 */
	protected $thread;

	/**
	 * @var \nick97\TraktMovies\Service\Movie\Creator
	 */
	protected $movieCreator;

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app);
		$this->thread = $thread;
		$this->movieCreator = $this->service('nick97\TraktMovies:Movie\Creator', $thread, $dummyId);
		$this->movieCreator->setIsAutomated();
	}

	/**
	 * @return \nick97\TraktMovies\XF\Entity\Thread
	 */
	public function getThread()
	{
		return $this->thread;
	}

	/**
	 * @return \nick97\TraktMovies\Service\Movie\Creator
	 */
	public function getMovieCreator()
	{
		return $this->movieCreator;
	}

	protected function _validate()
	{
		$movieCreator = $this->movieCreator;
		$movieCreator->setComment($this->thread->getOption('movieOriginalMessage'));

		if (!$this->movieCreator->validate($errors)) {
			return $errors;
		} else {
			return [];
		}
	}

	protected function _save()
	{
		$movie = $this->movieCreator->save();

		if ($movie && \XF::isAddOnActive('ThemeHouse/Covers')) {
			$this->updateCoverImage($movie);
		}

		return $movie;
	}

	public function updateCoverImage(\nick97\TraktMovies\Entity\Movie $movie)
	{
		$backdropSize = $this->app->options()->traktthreads_backdropCoverSize;
		if ($backdropSize == 'none') {
			return;
		}

		$thread = $this->thread;
		$movieData = $thread->getOption('movieApiResponse');
		if (empty($movieData['backdrop_path'])) {
			return;
		}

		/** @var \nick97\TraktMovies\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('nick97\TraktMovies:Thread\Cover', $movie);
		$coverService->update($movieData['backdrop_path']);
	}
}
