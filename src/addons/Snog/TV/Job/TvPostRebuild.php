<?php

namespace Snog\TV\Job;

use GuzzleHttp\Exception\RequestException;

class TvPostRebuild extends \XF\Job\AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT post_id
				FROM xf_snog_tv_post
				WHERE post_id > ?
				ORDER BY post_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \Snog\TV\Entity\TVPost $episode */
		$episode = $this->app->em()->find('Snog\TV:TVPost', $id);
		if ($episode)
		{
			/** @var \Snog\TV\Helper\Tmdb\Api $apiHelper */
			$apiHelper = \XF::helper('Snog\TV:Tmdb\Api');
			$tmdbClient = $apiHelper->getClient();

			try
			{
				$response = $tmdbClient->getTv($episode->Post->Thread->TV->tv_id)
					->getSeason($episode->tv_season)
					->getEpisode($episode->tv_episode)
					->getDetails(['credits']);
			}
			catch (RequestException $exception)
			{
				\XF::logError('TV Episode rebuild error: ' . $exception->getMessage());
				return;
			}

			if ($tmdbClient->hasError())
			{
				\XF::logError('TV Episode rebuild error: ' . $tmdbClient->getError());
				return;
			}

			if (!$response)
			{
				return;
			}

			$episode->setFromApiResponse($response);

			if ($this->app->options()->TvThreads_useLocalImages && $episode->tv_image)
			{
				/** @var \Snog\TV\Service\TVPost\Image $imageService */
				$imageService = $this->app->service('Snog\TV:TVPost\Image', $episode);
				$imageService->setImageFromApiPath($episode->tv_image, 'w300');

				$imageService->updateImage();
			}

			$episode->saveIfChanged();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('snog_tv_rebuild_episodes');
	}
}