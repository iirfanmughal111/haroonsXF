<?php

namespace Snog\Movies\Job;

class MovieProductionCompaniesRebuild extends \XF\Job\AbstractJob
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
				FROM xf_snog_movies_thread
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
			$this->data['total'] = $db->fetchOne("SELECT COUNT(thread_id) FROM xf_snog_movies_thread");
		}

		\XF::setMemoryLimit(-1);

		$done = 0;

		foreach ($ids as $id)
		{
			$this->data['count']++;
			$this->data['start'] = $id;

			/** @var \Snog\Movies\Entity\Movie $movie */
			$movie = $this->app->em()->find('Snog\Movies:Movie', $id);
			if (!$movie)
			{
				continue;
			}

			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$apiResponse = $tmdbClient->getMovie($movie->tmdb_id)->getDetails();
			if ($tmdbClient->hasError())
			{
				continue;
			}

			if (!isset($apiResponse['production_companies']))
			{
				continue;
			}

			$companyIds = array_column($apiResponse['production_companies'], 'id');
			$movie->tmdb_production_company_ids = $companyIds;
			$movie->saveIfChanged();

			$this->data['companyIds'] = array_merge($this->data['companyIds'], $companyIds);

			$done++;

			if (microtime(true) - $startTime >= $maxRunTime)
			{
				break;
			}
		}

		$this->app->jobManager()->enqueueAutoBlocking('Snog\Movies:MovieNewCompanies', [
			'companyIds' => array_unique($this->data['companyIds'])
		]);

		$this->data['batch'] = $this->calculateOptimalBatch($this->data['batch'], $done, $startTime, $maxRunTime, 1000);

		return $this->resume();
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('snog_movies_rebuild_movie_production_companies');
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