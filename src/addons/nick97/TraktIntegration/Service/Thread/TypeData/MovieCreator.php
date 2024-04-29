<?php


namespace nick97\TraktIntegration\Service\Thread\TypeData;

use XF\Service\Thread\TypeData\SaverInterface;

class MovieCreator extends \Snog\Movies\Service\Thread\TypeData\MovieCreator
{
	public function __construct(\XF\App $app, \XF\Entity\Thread $thread, $dummyId = null)
	{
		parent::__construct($app, $thread);
		$this->thread = $thread;
		$this->movieCreator = $this->service('Snog\Movies:Movie\Creator', $thread, $dummyId);
		$this->movieCreator->setIsAutomated();
	}
}
