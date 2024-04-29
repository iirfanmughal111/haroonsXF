<?php

namespace nick97\TraktTV\Cron;

use XF\InputFilterer;
use XF\Util\File;

class EpisodeUpdate
{
	public static function Process()
	{
		$app = \XF::app();

		/** @var \nick97\TraktTV\Entity\TVPost[]|\XF\Mvc\Entity\AbstractCollection $episodes */
		$episodes = $app->finder('nick97\TraktTV:TVPost')
			->where('tv_id', '>', 0)
			->where('tv_checked', 0)
			->whereOr(
				['tv_image', '=', ''],
				['tv_plot', '=', '']
			)
			->fetch(3);

		/** @var \nick97\TraktTV\Helper\Trakt\Api $apiHelper */
		$apiHelper = \XF::helper('nick97\TraktTV:Trakt\Api');
		$traktClient = $apiHelper->getClient();

		foreach ($episodes as $episode) {
			$tvepisode = $traktClient->getTv($episode->tv_id)
				->getSeason($episode->tv_season)
				->getEpisode($episode->tv_episode)
				->getDetails(['credits', 'videos']);

			if ($traktClient->hasError()) {
				continue;
			}

			// IMAGE MISSING - GET IT IF AVAILABLE
			if ($episode->tv_image == '' && !is_null($tvepisode['still_path'])) {
				// GET EPISODE IMAGE
				if ($tvepisode['still_path'] > '') {
					$image = str_ireplace('/', '', $tvepisode['still_path']);
					$path = 'data://tv/EpisodePosters' . '/' . $episode->post_id . '-' . $image;
					$tempDir = FILE::getTempDir();
					$tempPath = $tempDir . $tvepisode['still_path'];

					$imageUtil = new \nick97\TraktTV\Trakt\Image();
					$poster = $imageUtil->getImage($tvepisode['still_path'], 'w300');

					if (file_exists($tempPath)) {
						continue;
					}
					file_put_contents($tempPath, $poster);

					File::copyFileToAbstractedPath($tempPath, $path);
					unlink($tempPath);

					$episode->tv_image = $tvepisode['still_path'];
				}
			}

			// PLOT MISSING - GET IT IF AVAILABLE
			if ($episode->tv_plot == '' && !is_null($tvepisode['overview'])) {
				$episode->tv_plot = $tvepisode['overview'];
			}

			// CAST MISSING - GET IT IF AVAILABLE
			// THIS IS NOT A QUERY CONDITION BECAUSE THE CAST SHOULD NOT BE DIFFERENT FROM THE MAIN TV SHOW CAST
			// BUT IF IT EXISTS WE'LL ADD IT TO THIS EPISODE DATA
			$permStars = '';
			if ($episode->tv_cast == '' && !empty($tvepisode['credits']['cast'])) {
				foreach ($tvepisode['credits']['cast'] as $cast) {
					if ($permStars) $permStars .= ', ';
					$permStars .= $cast['name'];
				}

				$episode->tv_cast = $permStars;
			}

			// GUEST STAR LIST MISSING - GET IT IF AVAILABLE
			// THIS IS NOT A QUERY CONDITION BECAUSE GUEST STARS ARE NOT ALWAYS LISTED FOR EPISODES
			// BUT IF IT EXISTS WE'LL ADD IT TO THIS EPISODE DATA
			if ($episode->tv_guest == '' && !empty($tvepisode['credits']['guest_stars'])) {
				if (!$permStars) {
					$permStars = '';

					foreach ($tvepisode['credits']['cast'] as $cast) {
						if ($permStars) $permStars .= ', ';
						$permStars .= $cast['name'];
					}
				}

				$checkStars = explode(',', $permStars);

				$guest = '';
				foreach ($tvepisode['credits']['guest_stars'] as $guestStar) {
					if (!in_array($guestStar['name'], $checkStars)) {
						if ($guest) $guest .= ', ';
						$guest .= $guestStar['name'];
					}
				}

				$episode->tv_guest = $guest;
			}

			$episode->tv_checked = 1;
			$episode->save(false);
		}

		// RESET ALL CHECKED - RESTARTS CHECK CYCLE FROM THE BEGINNING
		if (!$episodes->toArray()) {
			$db = \XF::db();
			$db->update('nick97_trakt_tv_post', ['tv_checked' => 0],  'tv_checked = 1');
		}
	}
}
