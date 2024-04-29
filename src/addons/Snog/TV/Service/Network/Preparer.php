<?php

namespace Snog\TV\Service\Network;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\Entity\Network
	 */
	protected $network;

	public function __construct(\XF\App $app, \Snog\TV\Entity\Network $network)
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