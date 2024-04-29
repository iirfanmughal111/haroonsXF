<?php

namespace Snog\Movies\Service\Person;

use Snog\Movies\Tmdb\Image;
use XF\Service\ValidateAndSavableTrait;
use XF\Util\File;

class Editor extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var \Snog\Movies\Entity\Person
	 */
	protected $person;

	/**
	 * @var Preparer
	 */
	protected $preparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, \Snog\Movies\Entity\Person $person)
	{
		parent::__construct($app);
		$this->person = $person;

		$this->preparer = $this->service('Snog\Movies:Person\Preparer', $person);
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function setPerformValidations($perform)
	{
		$this->performValidations = (bool)$perform;
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

		$this->preparer->afterUpdate();
		return $person;
	}
}
