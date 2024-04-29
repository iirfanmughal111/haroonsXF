<?php

namespace Snog\TV\Service\Network;

use XF\Service\ValidateAndSavableTrait;

class Creator extends \XF\Service\AbstractService
{
	use ValidateAndSavableTrait;

	/**
	 * @var \Snog\TV\Entity\Network
	 */
	protected $network;

	/**
	 * @var Preparer
	 */
	protected $preparer;

	protected $performValidations = true;

	public function __construct(\XF\App $app, $networkId)
	{
		parent::__construct($app);
		$this->network = $this->em()->create('Snog\TV:Network');
		$this->network->network_id = $networkId;

		$this->preparer = $this->service('Snog\TV:Network\Preparer', $this->network);
	}

	public function getNetwork()
	{
		return $this->network;
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
		$this->network->setFromApiResponse($apiResponse);
	}

	public function setLogoPath($logoPath)
	{
		$this->network->logo_path = strval($logoPath);
	}

	public function setName($name)
	{
		$this->network->name = strval($name);
	}

	public function setHeadquarters($headquarters)
	{
		$this->network->headquarters = $headquarters;
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->network->preSave();
		return $this->network->getErrors();
	}

	protected function _save()
	{
		$person = $this->network;
		$person->save();

		$this->preparer->afterInsert();
		return $person;
	}
}