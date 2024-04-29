<?php

namespace nick97\TraktTV\Job;

use GuzzleHttp\Exception\RequestException;

class TvPostRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT post_id
				FROM nick97_trakt_tv_post
				WHERE post_id > ?
				ORDER BY post_id
			",
			$batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \nick97\TraktTV\Entity\TVPost $episode */
		$episode = $this->app->em()->find('nick97\TraktTV:TVPost', $id);
		if ($episode) {
			/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
			$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
			$traktClient = $apiHelper->getClient();

			try {
				$response = $traktClient->getTv($episode->Post->Thread->TV->tv_id)
					->getSeason($episode->tv_season)
					->getEpisode($episode->tv_episode)
					->getDetails(['credits']);
			} catch (RequestException $exception) {
				\XF::logError('TV Episode rebuild error: ' . $exception->getMessage());
				return;
			}

			if ($traktClient->hasError()) {
				\XF::logError('TV Episode rebuild error: ' . $traktClient->getError());
				return;
			}

			if (!$response) {
				return;
			}

			$episode->setFromApiResponse($response);

			if ($this->app->options()->traktTvThreads_useLocalImages && $episode->tv_image) {
				/** @var \nick97\TraktTV\Service\TVPost\Image $imageService */
				$imageService = $this->app->service('nick97\TraktTV:TVPost\Image', $episode);
				$imageService->setImageFromApiPath($episode->tv_image, 'w300');

				$imageService->updateImage();
			}

			$episode->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('trakt_tv_rebuild_episodes');
	}
}
