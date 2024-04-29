<?php

namespace Snog\TV\Job;


class TvCompanyImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT company_id
				FROM xf_snog_tv_company
				WHERE company_id > ? AND logo_path != ''
				ORDER BY company_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\Company $company */
		$company = $this->app->em()->find('Snog\TV:Company', $id);
		if (!$company)
		{
			return;
		}

		/** @var \Snog\TV\Service\Company\Image $imageService */
		$imageService = $this->app->service('Snog\TV:Company\Image', $company);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->TvThreads_largeNetworkLogoSize);
		if (!$imageService->updateImage())
		{
			$company->logo_path = '';
		}

		$company->saveIfChanged();
	}

	public function canTriggerByChoice()
	{
		return true;
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_networks');
	}
}