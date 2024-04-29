<?php

namespace Snog\Movies\Service\Company;


use XF\Service\ValidateAndSavableTrait;

class Creator extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var \Snog\Movies\Entity\Company
	 */
	protected $company;

	/**
	 * @var Preparer
	 */
	protected $preparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, $companyId)
	{
		parent::__construct($app);
		$this->company = $this->em()->create('Snog\Movies:Company');
		$this->company->company_id = $companyId;

		$this->preparer = $this->service('Snog\Movies:Company\Preparer', $this->company);
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool) $perform;
	}

	public function getPerformValidations()
	{
		return $this->performValidations;
	}

	public function setIsAutomated()
	{
		$this->setPerformValidations(false);
	}

	public function setFromApiResponse(array $apiResponse)
	{
		$this->company->setFromApiResponse($apiResponse);
	}

	public function setLogoPath($logoPath)
	{
		$this->company->logo_path = strval($logoPath);
	}

	public function setName($name)
	{
		$this->company->name = strval($name);
	}

	public function setDescription($description)
	{
		$this->company->description = $description;
	}

	public function setHeadquarters($headquarters)
	{
		$this->company->headquarters = $headquarters;
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->company->preSave();
		return $this->company->getErrors();
	}

	protected function _save()
	{
		$person = $this->company;
		$person->save();

		$this->preparer->afterInsert();
		return $person;
	}
}