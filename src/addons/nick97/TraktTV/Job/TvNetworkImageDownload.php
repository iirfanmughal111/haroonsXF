<?php

namespace nick97\TraktTV\Job;


class TvNetworkImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT network_id
				FROM nick97_trakt_tv_network
				WHERE network_id > ? AND logo_path != ''
				ORDER BY network_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\Network $network */
		$network = $this->app->em()->find('nick97\TraktTV:Network', $id);
		if (!$network) {
			return;
		}

		/** @var \nick97\TraktTV\Service\Network\Image $imageService */
		$imageService = $this->app->service('nick97\TraktTV:Network\Image', $network);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($network->logo_path, $this->app->options()->traktTvThreads_largeCompanyLogoSize);
		if (!$imageService->updateImage()) {
			$network->logo_path = '';
		}

		$network->saveIfChanged();
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
