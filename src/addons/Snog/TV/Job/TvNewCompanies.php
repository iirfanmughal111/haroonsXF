<?php

namespace Snog\TV\Job;

class TvNewCompanies extends \XF\Job\AbstractJob
{
	protected $defaultData = [
		'total' => 0,
		'count' => 0,
		'companyIds' => [],
	];

	/**
	 * @inheritDoc
	 */
	public function run($maxRunTime)
	{
		$startTime = microtime(true);
		$em = $this->app->em();

		if (empty($this->data['companyIds']))
		{
			return $this->complete();
		}

		$companyIds = $this->prepareCompanyData();
		if (!$companyIds)
		{
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($companyIds as $key => $id)
		{
			$this->data['count']++;
			$this->data['start'] = $id;
			unset($companyIds[$key]);

			/** @var \Snog\TV\Entity\Company $company */
			$company = $em->find('Snog\TV:Company', $id);
			if ($company)
			{
				continue;
			}

			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			$companyData = $tmdbClient->getCompany()->getDetails($id);
			if ($tmdbClient->hasError())
			{
				continue;
			}

			$creator = $this->setupCompanyCreate($companyData);
			$creator->save();

			$this->finalizeCompanyCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime)
			{
				break;
			}
		}

		if (is_array($this->data['companyIds']))
		{
			$this->data['companyIds'] = $companyIds;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupCompanyCreate(array $companyData)
	{
		/** @var \Snog\TV\Service\Company\Creator $creator */
		$creator = $this->app->service('Snog\TV:Company\Creator', $companyData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($companyData);

		return $creator;
	}

	protected function finalizeCompanyCreate(\Snog\TV\Service\Company\Creator $creator)
	{
		$company = $creator->getCompany();
		if ($this->app->options()->TvThreads_useLocalImages && $company->logo_path)
		{
			/** @var \Snog\TV\Service\Company\Image $imageService */
			$imageService = $this->app->service('Snog\TV:Company\Image', $company);

			$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->TvThreads_largeCompanyLogoSize);
			$imageService->updateImage();
		}
	}

	protected function prepareCompanyData()
	{
		if (!is_array($this->data['companyIds']))
		{
			throw new \LogicException("New companyIds values must be an array");
		}

		$newPersons = $this->data['companyIds'];
		sort($newPersons, SORT_NUMERIC);
		return $newPersons;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('snog_tv_insert_new_companies...');
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