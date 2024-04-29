<?php

namespace Snog\Movies\Job;

class MovieNewPersons extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'total' => 0,
		'count' => 0,
		'newPersons' => [],
	];

	/**
	 * @inheritDoc
	 */
	public function run($maxRunTime)
	{
		$startTime = microtime(true);
		$em = $this->app->em();

		if (empty($this->data['newPersons']))
		{
			return $this->complete();
		}

		$personsData = $this->preparePersonData();
		if (!$personsData)
		{
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($personsData as $key => $personData)
		{
			$this->data['count']++;
			$this->data['start'] = $key;
			unset($personsData[$key]);

			/** @var \Snog\Movies\Entity\Person $person */
			$person = $em->find('Snog\Movies:Person', $personData['id']);
			if ($person)
			{
				continue;
			}

			$creator = $this->setupPersonCreate($personData);
			$creator->save();

			$this->finalizePersonCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime)
			{
				break;
			}
		}

		if (is_array($this->data['newPersons']))
		{
			$this->data['newPersons'] = $personsData;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupPersonCreate(array $personData)
	{
		/** @var \Snog\Movies\Service\Person\Creator $creator */
		$creator = $this->app->service('Snog\Movies:Person\Creator', $personData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($personData);

		return $creator;
	}

	protected function finalizePersonCreate(\Snog\Movies\Service\Person\Creator $creator)
	{
		$person = $creator->getPerson();

		/** @var \Snog\Movies\Service\Person\Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Person\Image', $person);

		$imageService->setImageFromApiPath($person->profile_path);
		$imageService->updateImage();
	}

	protected function preparePersonData()
	{
		if (!is_array($this->data['newPersons']))
		{
			throw new \LogicException("New person values must be an array");
		}

		$newPersons = $this->data['newPersons'];
		sort($newPersons, SORT_NUMERIC);
		return $newPersons;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('snog_movies_insert_new_persons');
	}

	public function canCancel()
	{
		return false;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
}