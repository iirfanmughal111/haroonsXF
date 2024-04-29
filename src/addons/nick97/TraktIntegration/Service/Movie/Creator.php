<?php


namespace nick97\TraktIntegration\Service\Movie;


class Creator extends \Snog\Movies\Service\Movie\Creator
{
	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app, $thread);
		$this->setThread($thread, $dummyId);
		$this->moviePreparer = $this->service('Snog\Movies:Movie\Preparer', $this->movie);
	}

	protected function setThread(\XF\Entity\Thread $thread, $dummyId = null)
	{
		$this->thread = $thread;

		/** @var \Snog\Movies\Entity\Movie $movie */
		$movie = $this->em()->create('Snog\Movies:Movie');
		$threadId = $thread->thread_id;
		if (!$threadId) {
			$threadId = $movie->em()->getDeferredValue(function () use ($thread) {
				return $thread->thread_id;
			}, 'save');
		}

		if ($dummyId) {
			$movie->thread_id = $dummyId;
		} else {

			$movie->thread_id = $threadId;
		}
		$this->apiResponse = $this->thread->getOption('movieApiResponse');

		$this->movie = $movie;
	}
}
