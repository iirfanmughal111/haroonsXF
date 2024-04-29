<?php

namespace Snog\Movies\Job;

class MovieNewCompanies extends \XF\Job\AbstractJob
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

			/** @var \Snog\Movies\Entity\Company $company */
			$company = $em->find('Snog\Movies:Company', $id);
			if ($company)
			{
				continue;
			}

			/** @var \Snog\Movies\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\Movies:Tmdb\Api');
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
		/** @var \Snog\Movies\Service\Company\Creator $creator */
		$creator = $this->app->service('Snog\Movies:Company\Creator', $companyData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($companyData);

		return $creator;
	}

	protected function finalizeCompanyCreate(\Snog\Movies\Service\Company\Creator $creator)
	{
		$company = $creator->getCompany();

		/** @var \Snog\Movies\Service\Company\Image $imageService */
		$imageService = $this->app->service('Snog\Movies:Company\Image', $company);

		$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->tmdbthreads_largeCompanyLogoSize);
		$imageService->updateImage();
	}

	protected function prepareCompanyData()
	{
		if (!is_array($this->data['companyIds']))
		{
			throw new \LogicException("New companyIds values must be an array");
		}

		$companyIds = $this->data['companyIds'];
		sort($companyIds, SORT_NUMERIC);
		return $companyIds;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('snog_movies_insert_new_companies...');
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