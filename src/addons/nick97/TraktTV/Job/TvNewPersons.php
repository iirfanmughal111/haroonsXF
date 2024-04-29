<?php

namespace nick97\TraktTV\Job;

class TvNewPersons extends \XF\Job\AbstractJob
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

		if (empty($this->data['newPersons'])) {
			return $this->complete();
		}

		$personsData = $this->preparePersonData();
		if (!$personsData) {
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($personsData as $key => $personData) {
			$this->data['count']++;
			$this->data['start'] = $key;
			unset($personsData[$key]);

			/** @var \nick97\TraktTV\Entity\Person $person */
			$person = $em->find('nick97\TraktTV:Person', $personData['id']);
			if ($person) {
				continue;
			}

			$creator = $this->setupPersonCreate($personData);
			if (!$creator->validate($errors)) {
				continue;
			}
			$creator->save();

			$this->finalizePersonCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime) {
				break;
			}
		}

		if (is_array($this->data['newPersons'])) {
			$this->data['newPersons'] = $personsData;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupPersonCreate(array $personData)
	{
		/** @var \nick97\TraktTV\Service\Person\Creator $creator */
		$creator = $this->app->service('nick97\TraktTV:Person\Creator', $personData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($personData);

		return $creator;
	}

	protected function finalizePersonCreate(\nick97\TraktTV\Service\Person\Creator $creator)
	{
		$person = $creator->getPerson();
		if ($this->app->options()->traktTvThreads_useLocalImages && $person->profile_path) {
			/** @var \nick97\TraktTV\Service\Person\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:Person\Image', $person);

			$imageService->setImageFromApiPath($person->profile_path, 'w300_and_h450_bestv2');
			$imageService->updateImage();
		}
	}

	protected function preparePersonData()
	{
		if (!is_array($this->data['newPersons'])) {
			throw new \LogicException("New person values must be an array");
		}

		$newPersons = $this->data['newPersons'];
		sort($newPersons, SORT_NUMERIC);
		return $newPersons;
	}

	public function getStatusMessage()
	{
		// TODO: Implement getStatusMessage() method.
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
