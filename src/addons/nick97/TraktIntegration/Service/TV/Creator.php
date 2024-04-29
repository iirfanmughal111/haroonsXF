<?php

namespace nick97\TraktIntegration\Service\TV;


class Creator extends \Snog\TV\Service\TV\Creator
{

	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app, $thread);
		$this->setThread($thread, $dummyId);

		$this->tvPreparer = $this->service('Snog\TV:TV\Preparer', $this->tv);
	}

	protected function setThread(\XF\Entity\Thread $thread, $dummyId = null)
	{
		$this->thread = $thread;

		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->em()->create('Snog\TV:TV');
		$threadId = $thread->thread_id;
		if (!$threadId) {
			$threadId = $tv->em()->getDeferredValue(function () use ($thread) {
				return $thread->thread_id;
			}, 'save');
		}

		// $tv->thread_id = $threadId;

		if ($dummyId) {
			$tv->thread_id = $dummyId;
		} else {

			$tv->thread_id = $threadId;
		}

		$this->tvData = $this->thread->getOption('tvData');

		$this->tv = $tv;
	}
}
