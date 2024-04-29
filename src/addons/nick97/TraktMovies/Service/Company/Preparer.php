<?php

namespace nick97\TraktMovies\Service\Company;


class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var \nick97\TraktMovies\Entity\Company
	 */
	protected $company;

	public function __construct(\XF\App $app, \nick97\TraktMovies\Entity\Company $company)
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
