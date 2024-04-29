<?php

namespace nick97\TraktIntegration\Service\Thread\TypeData;


use XF\Service\Thread\TypeData\SaverInterface;

class TvCreator extends \Snog\TV\Service\Thread\TypeData\TvCreator
{
	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app, $thread);
		$this->thread = $thread;
		$this->tvCreator = $this->service('Snog\TV:TV\Creator', $thread, $dummyId);
		$this->tvCreator->setIsAutomated();
	}
}
