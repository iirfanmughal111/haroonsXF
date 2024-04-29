<?php

namespace nick97\TraktTV\Service\Network;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktTV\Entity\Network
	 */
	protected $network;

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\Network $network)
	{
		parent::__construct($app);
		$this->network = $network;
	}

	public function getNetwork()
	{
		return $this->network;
	}

	public function afterInsert()
	{
	}

	public function afterUpdate()
	{
	}
}
