<?php

namespace Snog\TV\Service\Company;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\TV\Entity\Company
	 */
	protected $company;

	public function __construct(\XF\App $app, \Snog\TV\Entity\Company $company)
	{
		parent::__construct($app);
		$this->company = $company;
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function afterInsert()
	{
	}

	public function afterUpdate()
	{
	}
}