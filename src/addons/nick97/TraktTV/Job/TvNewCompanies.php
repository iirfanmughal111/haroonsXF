<?php

namespace nick97\TraktTV\Job;

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

		if (empty($this->data['companyIds'])) {
			return $this->complete();
		}

		$companyIds = $this->prepareCompanyData();
		if (!$companyIds) {
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($companyIds as $key => $id) {
			$this->data['count']++;
			$this->data['start'] = $id;
			unset($companyIds[$key]);

			/** @var \nick97\TraktTV\Entity\Company $company */
			$company = $em->find('nick97\TraktTV:Company', $id);
			if ($company) {
				continue;
			}

			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			$companyData = $traktClient->getCompany()->getDetails($id);
			if ($traktClient->hasError()) {
				continue;
			}

			$creator = $this->setupCompanyCreate($companyData);
			$creator->save();

			$this->finalizeCompanyCreate($creator);

			if ($limitTime && microtime(true) - $startTime > $maxRunTime) {
				break;
			}
		}

		if (is_array($this->data['companyIds'])) {
			$this->data['companyIds'] = $companyIds;
		}

		$db->commit();

		return $this->resume();
	}

	protected function setupCompanyCreate(array $companyData)
	{
		/** @var \nick97\TraktTV\Service\Company\Creator $creator */
		$creator = $this->app->service('nick97\TraktTV:Company\Creator', $companyData['id']);
		$creator->setIsAutomated();
		$creator->setFromApiResponse($companyData);

		return $creator;
	}

	protected function finalizeCompanyCreate(\nick97\TraktTV\Service\Company\Creator $creator)
	{
		$company = $creator->getCompany();
		if ($this->app->options()->traktTvThreads_useLocalImages && $company->logo_path) {
			/** @var \nick97\TraktTV\Service\Company\Image $imageService */
			$imageService = $this->app->service('nick97\TraktTV:Company\Image', $company);

			$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->traktTvThreads_largeCompanyLogoSize);
			$imageService->updateImage();
		}
	}

	protected function prepareCompanyData()
	{
		if (!is_array($this->data['companyIds'])) {
			throw new \LogicException("New companyIds values must be an array");
		}

		$newPersons = $this->data['companyIds'];
		sort($newPersons, SORT_NUMERIC);
		return $newPersons;
	}

	public function getStatusMessage()
	{
		return \XF::phrase('trakt_tv_insert_new_companies...');
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
