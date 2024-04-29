<?php

namespace nick97\TraktTV\Job;


class TvCompanyImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT company_id
				FROM nick97_trakt_tv_company
				WHERE company_id > ? AND logo_path != ''
				ORDER BY company_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\Company $company */
		$company = $this->app->em()->find('nick97\TraktTV:Company', $id);
		if (!$company) {
			return;
		}

		/** @var \nick97\TraktTV\Service\Company\Image $imageService */
		$imageService = $this->app->service('nick97\TraktTV:Company\Image', $company);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($company->logo_path, $this->app->options()->traktTvThreads_largeNetworkLogoSize);
		if (!$imageService->updateImage()) {
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
		return \XF::phrase('trakt_tv_rebuild_networks');
	}
}
