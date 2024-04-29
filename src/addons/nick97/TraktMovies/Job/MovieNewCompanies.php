<?php

namespace nick97\TraktMovies\Job;

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

			/** @var \nick97\TraktMovies\Entity\Company $company */
			$company = $em->find('nick97\TraktMovies:Company', $id);
			if ($company)
			{
				continue;
			}

			/** @var \nick97\TraktMovies\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktMovies:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$companyData = $traktClient->getCompany()->getDetails($id);
			if ($traktClient->hasError())
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
		/** @var \nick97\TraktMovies\Service\Company\Creator $creator */
		$creator = $this->app->service('nick97\TraktMovies:Company\Creator', $companyData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($companyData);

		return $creator;
	}

	protected function finalizeCompanyCreate(\nick97\TraktMovies\Service\Company\Creator $creator)
	{
		$company = $creator->getCompany();

		/** @var \nick97\TraktMovies\Service\Company\Image $imageService */
		$imageService = $this->app->service('nick97\TraktMovies:Company\Image', $company);

		$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->traktthreads_largeCompanyLogoSize);
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
		return \XF::phrase('trakt_movies_insert_new_companies...');
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