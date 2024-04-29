<?php

namespace Snog\TV\Service\TV;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\Entity\TV
	 */
	protected $tv;

	public function __construct(\XF\App $app, \Snog\TV\Entity\TV $tv)
	{
		parent::__construct($app);
		$this->tv = $tv;
	}

	public function getTv()
	{
		return $this->tv;
	}

	public function afterInsert()
	{
	}

	public function afterUpdate()
	{
	}
}