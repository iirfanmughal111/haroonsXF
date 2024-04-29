<?php

namespace Snog\TV\Job;

class TvProductionCompaniesRebuild extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'start' => 0,
		'batch' => 100,
		'count' => 0,
		'total' => null,
		'companyIds' => []
	];

	public function run($maxRunTime)
	{
		$startTime = microtime(true);

		$db = $this->app->db();

		$ids = $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM xf_snog_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $this->data['batch']
		), $this->data['start']);
		if (!$ids)
		{
			return $this->complete();
		}

		if ($this->data['total'] === null)
		{
			$this->data['total'] = $db->fetchOne("SELECT COUNT(thread_id) FROM xf_snog_tv_thread");
		}

		\XF::setMemoryLimit(-1);

		$done = 0;

		foreach ($ids as $id)
		{
			$this->data['count']++;
			$this->data['start'] = $id;

			/** @var \Snog\TV\Entity\TV $show */
			$show = $this->app->em()->find('Snog\TV:TV', $id);
			if (!$show)
			{
				continue;
			}

			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getTv($show->tv_id)->getDetails();
			if ($tmdbClient->hasError())
			{
				continue;
			}

			if (!isset($apiResponse['production_companies']))
			{
				continue;
			}

			$companyIds = array_column($apiResponse['production_companies'], 'id');
			$show->tmdb_production_company_ids = $companyIds;
			$show->saveIfChanged();

			$this->data['companyIds'] = array_merge($this->data['companyIds'], $companyIds);

			$done++;

			if (microtime(true) - $startTime >= $maxRunTime)
			{
				break;
			}
		}

		/** @see TvNewCompanies */
		$this->app->jobManager()->enqueue('Snog\TV:TvNewCompanies', [
			'companyIds' => array_unique($this->data['companyIds'])
		]);

		$this->data['batch'] = $this->calculateOptimalBatch($this->data['batch'], $done, $startTime, $maxRunTime, 1000);

		return $this->resume();
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('snog_tv_rebuild_tv_production_companies');
		return sprintf('%s.. (%d / %d)', $actionPhrase, $this->data['count'], $this->data['total']);
	}

	public function canCancel()
	{
		return true;
	}

	public function canTriggerByChoice()
	{
		return true;
	}
}