<?php

namespace Snog\TV\Job;

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

		if (empty($this->data['networkIds']))
		{
			return $this->complete();
		}

		$networkIds = $this->prepareNetworkData();
		if (!$networkIds)
		{
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($networkIds as $key => $id)
		{
			$this->data['count']++;
			$this->data['start'] = $id;
			unset($networkIds[$key]);

			/** @var \Snog\TV\Entity\Network $network */
			$network = $em->find('Snog\TV:Network', $id);
			if ($network)
			{
				continue;
			}

			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$networkData = $tmdbClient->getNetwork()->getDetails($id);
			if ($tmdbClient->hasError())
			{
				continue;
			}

			$creator = $this->setupNetworkCreate($networkData);
			$creator->save();

			$this->finalizeNetworkCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime)
			{
				break;
			}
		}

		if (is_array($this->data['networkIds']))
		{
			$this->data['networkIds'] = $networkIds;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupNetworkCreate(array $networkData)
	{
		/** @var \Snog\TV\Service\Network\Creator $creator */
		$creator = $this->app->service('Snog\TV:Network\Creator', $networkData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($networkData);

		return $creator;
	}

	protected function finalizeNetworkCreate(\Snog\TV\Service\Network\Creator $creator)
	{
		$network = $creator->getNetwork();
		if ($this->app->options()->TvThreads_useLocalImages && $network->logo_path)
		{
			/** @var \Snog\TV\Service\Network\Image $imageService */
			$imageService = $this->app->service('Snog\TV:Network\Image', $network);

			$imageService->setImageFromApiPath($network->logo_path, $this->app->options()->TvThreads_largeNetworkLogoSize);
			$imageService->updateImage();
		}
	}

	protected function prepareNetworkData()
	{
		if (!is_array($this->data['networkIds']))
		{
			throw new \LogicException("New networkIds values must be an array");
		}

		$networkIds = $this->data['networkIds'];
		sort($networkIds, SORT_NUMERIC);
		return $networkIds;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('snog_tv_insert_new_networks...');
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