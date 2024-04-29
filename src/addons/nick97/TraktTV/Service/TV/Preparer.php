<?php

namespace nick97\TraktTV\Service\TV;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktTV\Entity\TV
	 */
	protected $tv;

	public function __construct(\XF\App $app, \nick97\TraktTV\Entity\TV $tv)
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
