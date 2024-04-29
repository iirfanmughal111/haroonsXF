<?php

namespace nick97\TraktTV\Job;

class TvThreadCoverUpdate extends \XF\Job\AbstractRebuildJob
{
	public function run($maxRunTime)
	{
		if (!\XF::isAddOnActive('ThemeHouse/Covers')) {
			return $this->complete();
		}

		$backdropSize = $this->app->options()->traktTvThreads_backdropCoverSize;
		if ($backdropSize == 'none') {
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
				FROM nick97_trakt_tv_thread
				WHERE thread_id > ?
				ORDER BY thread_id
			",
			$batch
		), $start);
	}

	/**
	 * @param $id
	 * @return void
	 * @throws \XF\PrintableException
	 */
	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TV $tv */
		$tv = $this->app->em()->find('nick97\TraktTV:TV', $id);
		if (!$tv) {
			return;
		}

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		$tvData = $traktClient->getTv($tv->tv_id)->getDetails();
		if (empty($tvData['backdrop_path']) || $tv->backdrop_path == $tvData['backdrop_path']) {
			return;
		}

		/** @var \nick97\TraktTV\Service\Thread\Cover $coverService */
		$coverService = $this->app->service('nick97\TraktTV:Thread\Cover', $tv);
		$coverService->update($tvData['backdrop_path']);
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_update_thread_covers');
	}
}
