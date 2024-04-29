<?php

namespace Snog\TV\Job;


class TvNetworkImageDownload extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT network_id
				FROM xf_snog_tv_network
				WHERE network_id > ? AND logo_path != ''
				ORDER BY network_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\Network $network */
		$network = $this->app->em()->find('Snog\TV:Network', $id);
		if (!$network)
		{
			return;
		}

		/** @var \Snog\TV\Service\Network\Image $imageService */
		$imageService = $this->app->service('Snog\TV:Network\Image', $network);
		$imageService->deleteImageFiles();

		$imageService->setImageFromApiPath($network->logo_path, $this->app->options()->TvThreads_largeCompanyLogoSize);
		if (!$imageService->updateImage())
		{
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
		return \XF::phrase('snog_tv_rebuild_networks');
	}
}