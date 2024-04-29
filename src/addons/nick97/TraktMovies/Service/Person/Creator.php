<?php

namespace nick97\TraktMovies\Service\Person;

use Snog\TV\Service\Person\Preparer;
use XF\Service\ValidateAndSavableTrait;

class Creator extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var \nick97\TraktMovies\Entity\Person
	 */
	protected $person;

	/**
	 * @var Preparer
	 */
	protected $preparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, $personId)
	{
		parent::__construct($app);
		$this->person = $this->em()->create('nick97\TraktMovies:Person');
		$this->person->person_id = $personId;

		$this->preparer = $this->service('nick97\TraktMovies:Person\Preparer', $this->person);
	}

	public function getPerson()
	{
		return $this->person;
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
		$this->person->setFromApiResponse($apiResponse);
	}

	public function setProfilePath($profilePath)
	{
		$this->person->profile_path = strval($profilePath);
	}

	public function setGender($gender)
	{
		$this->person->gender = intval($gender);
	}

	public function setName($name)
	{
		$this->person->name = strval($name);
	}

	public function setKnownForDepartment($department)
	{
		$this->person->known_for_department = strval($department);
	}

	public function setPopularity($popularity)
	{
		$this->person->popularity = floatval($popularity);
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->person->preSave();
		return $this->person->getErrors();
	}

	protected function _save()
	{
		$person = $this->person;
		$person->save();

		$this->preparer->afterInsert();
		return $person;
	}
}
