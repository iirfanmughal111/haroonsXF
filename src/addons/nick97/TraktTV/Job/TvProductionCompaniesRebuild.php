<?php

namespace nick97\TraktTV\Job;

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
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			",
			$this->data['batch']
		), $this->data['start']);
		if (!$ids) {
			return $this->complete();
		}

		if ($this->data['total'] === null) {
			$this->data['total'] = $db->fetchOne("SELECT COUNT(thread_id) FROM nick97_trakt_tv_thread");
		}

		\XF::setMemoryLimit(-1);

		$done = 0;

		foreach ($ids as $id) {
			$this->data['count']++;
			$this->data['start'] = $id;

			/** @var \nick97\TraktTV\Entity\TV $show */
			$show = $this->app->em()->find('nick97\TraktTV:TV', $id);
			if (!$show) {
				continue;
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$apiResponse = $traktClient->getTv($show->tv_id)->getDetails();
			if ($traktClient->hasError()) {
				continue;
			}

			if (!isset($apiResponse['production_companies'])) {
				continue;
			}

			$companyIds = array_column($apiResponse['production_companies'], 'id');
			$show->trakt_production_company_ids = $companyIds;
			$show->saveIfChanged();

			$this->data['companyIds'] = array_merge($this->data['companyIds'], $companyIds);

			$done++;

			if (microtime(true) - $startTime >= $maxRunTime) {
				break;
			}
		}

		/** @see TvNewCompanies */
		$this->app->jobManager()->enqueue('nick97\TraktTV:TvNewCompanies', [
			'companyIds' => array_unique($this->data['companyIds'])
		]);

		$this->data['batch'] = $this->calculateOptimalBatch($this->data['batch'], $done, $startTime, $maxRunTime, 1000);

		return $this->resume();
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('trakt_tv_rebuild_tv_production_companies');
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
