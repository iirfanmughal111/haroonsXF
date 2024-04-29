<?php

namespace nick97\TraktTV\Job;

class TvNewNetworks extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'total' => 0,
		'count' => 0,
		'networkIds' => [],
	];

	/**
	 * @inheritDoc
	 */
	public function run($maxRunTime)
	{
		$startTime = microtime(true);
		$em = $this->app->em();

		if (empty($this->data['networkIds'])) {
			return $this->complete();
		}

		$networkIds = $this->prepareNetworkData();
		if (!$networkIds) {
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($networkIds as $key => $id) {
			$this->data['count']++;
			$this->data['start'] = $id;
			unset($networkIds[$key]);

			/** @var \nick97\TraktTV\Entity\Network $network */
			$network = $em->find('nick97\TraktTV:Network', $id);
			if ($network) {
				continue;
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$networkData = $traktClient->getNetwork()->getDetails($id);
			if ($traktClient->hasError()) {
				continue;
			}

			$creator = $this->setupNetworkCreate($networkData);
			$creator->save();

			$this->finalizeNetworkCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime) {
				break;
			}
		}

		if (is_array($this->data['networkIds'])) {
			$this->data['networkIds'] = $networkIds;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupNetworkCreate(array $networkData)
	{
		/** @var \nick97\TraktTV\Service\Network\Creator $creator */
		$creator = $this->app->service('nick97\TraktTV:Network\Creator', $networkData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($networkData);

		return $creator;
	}

	protected function finalizeNetworkCreate(\nick97\TraktTV\Service\Network\Creator $creator)
	{
		$network = $creator->getNetwork();
		if ($this->app->options()->traktTvThreads_useLocalImages && $network->logo_path) {
			/** @var \nick97\TraktTV\Service\Network\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:Network\Image', $network);

			$imageService->setImageFromApiPath($network->logo_path, $this->app->options()->traktTvThreads_largeNetworkLogoSize);
			$imageService->updateImage();
		}
	}

	protected function prepareNetworkData()
	{
		if (!is_array($this->data['networkIds'])) {
			throw new \LogicException("New networkIds values must be an array");
		}

		$networkIds = $this->data['networkIds'];
		sort($networkIds, SORT_NUMERIC);
		return $networkIds;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('trakt_tv_insert_new_networks...');
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
