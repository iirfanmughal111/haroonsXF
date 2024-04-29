<?php

namespace DBTech\Credits\Job;

use XF\Job\AbstractJob;

/**
 * Class EventDeleteCleanUp
 *
 * @package DBTech\Credits\Job
 */
class EventDeleteCleanUp extends AbstractJob
{
	/** @var array  */
	protected $defaultData = [
		'eventId' => null,
		'title' => null,

		'currentStep' => 0,
		'lastOffset' => null,

		'start' => 0
	];
	
	/**
	 * @param int $maxRunTime
	 *
	 * @return \XF\Job\JobResult
	 */
	public function run($maxRunTime): \XF\Job\JobResult
	{
		$this->data['start']++;

		if (!$this->data['eventId'] || !$this->data['title'])
		{
			return $this->complete();
		}

		/** @var \DBTech\Credits\Service\Event\DeleteCleanUp $deleter */
		$deleter = $this->app->service(
			'DBTech\Credits:Event\DeleteCleanUp',
			$this->data['eventId'],
			$this->data['title']
		);
		$deleter->restoreState($this->data['currentStep'], $this->data['lastOffset']);

		$result = $deleter->cleanUp($maxRunTime);
		if ($result->isCompleted())
		{
			return $this->complete();
		}
		
		$continueData = $result->getContinueData();
		$this->data['currentStep'] = $continueData['currentStep'];
		$this->data['lastOffset'] = $continueData['lastOffset'];
		
		return $this->resume();
	}
	
	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		$actionPhrase = \XF::phrase('deleting');
		$typePhrase = $this->data['title'];
		return sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, $this->data['start']);
	}

	/**
	 * @return bool
	 */
	public function canCancel(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function canTriggerByChoice(): bool
	{
		return false;
	}
}