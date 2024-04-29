<?php

namespace Snog\Movies\Service\Company;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \Snog\Movies\Entity\Company
	 */
	protected $company;

	public function __construct(\XF\App $app, \Snog\Movies\Entity\Company $company)
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