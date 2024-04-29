<?php

namespace Snog\TV\Job;

class TvThreadCoverUpdate extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers'))
		{
			return $this->complete();
		}

		$backdropSize = $this->app->options()->TvThreads_backdropCoverSize;
		if ($backdropSize == 'none')
		{
			return $this->complete();
		}

		return parent::run($maxRunTime);
	}

	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT thread_id
				FROM xf_snog_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			", $batch
		), $start);
	}

	/**
	 * @param $id
	 * @return void
	 * @throws \XF\PrintableException
	 */
	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TV $tv */
		$tv = $this->app->em()->find('Snog\TV:TV', $id);
		if (!$tv)
		{
			return;
		}

		/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
		$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
		$tmdbClient = $apiHelper->getClient();

		$tvData = $tmdbClient->getTv($tv->tv_id)->getDetails();
		if (empty($tvData['backdrop_path']) || $tv->backdrop_path == $tvData['backdrop_path'])
		{
			return;
		}

		/** @var \Snog\TV\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('Snog\TV:Thread\Cover', $tv);
		$coverService->update($tvData['backdrop_path']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_update_thread_covers');
	}
}