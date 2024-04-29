<?php

namespace nick97\TraktTV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\File;

/**
 * COLUMNS
 * @property int $post_id
 * @property int $tv_id
 * @property int $tv_season
 * @property int $tv_episode
 * @property string $tv_aired
 * @property string $tv_image
 * @property string $tv_title
 * @property string $tv_plot
 * @property string $tv_cast
 * @property string $tv_guest
 * @property string $message
 * @property int $tv_checked
 * @property int $tv_poster
 *
 * GETTERS
 * @property string $episode_image_url
 *
 * RELATIONS
 * @property \nick97\TraktTV\XF\Entity\Post $Post
 */
class TVPost extends Entity
{
	public function getTraktTvId()
	{
		$post = $this->Post;
		if (!empty($post->Thread->Forum->TVForum)) {
			if (!empty($post->Thread->Forum->TVForum->Parent)) {
				$tvId = $post->Thread->Forum->TVForum->Parent->tv_id;
			} else {
				$tvId = $post->Thread->Forum->TVForum->tv_id;
			}
		} else {
			$tvId = $post->Thread->TV->tv_id;
		}

		return $tvId;
	}

	public function setFromApiResponse(array $apiResponse)
	{
		/** @var \nick97\TraktTV\Helper\Trakt\Episode $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktTV:Trakt\Episode');

		$this->bulkSet([
			'tv_id' => $apiResponse['id'],
			'tv_season' => $apiResponse['season_number'] ?? 0,
			'tv_episode' => $apiResponse['episode_number'] ?? 0,
			'tv_aired' => $apiResponse['air_date'] ?? '',
			'tv_image' => $apiResponse['still_path'] ?? '',
			'tv_title' => $apiResponse['name'] ?? '',
			'tv_plot' => $apiResponse['overview'] ?? '',
			'tv_cast' => $traktHelper->getCastList($apiResponse, false),
			'tv_guest' => $traktHelper->getGuestStarsList($apiResponse),
		], ['forceConstraint' => true]);

		$this->setOption('apiResponse', $apiResponse);
	}

	public function getPostMessage()
	{
		$imageUrl = $this->getEpisodeImageUrl();

		$message = "[img]{$imageUrl}[/img]\r\n";
		$message .= "[B]{$this->tv_title}[/B]\r\n\r\n";
		$message .= "[B]" . \XF::phrase('trakt_tv_season') . ":[/B] " . $this->tv_season . "\r\n";
		$message .= "[B]" . \XF::phrase('trakt_tv_episode') . ":[/B] " . $this->tv_episode . "\r\n";
		if ($this->tv_aired) {
			$message .= "[B]" . \XF::phrase('trakt_tv_air_date') . ":[/B] " . $this->tv_aired . "\r\n\r\n";
		}
		if ($this->tv_cast) {
			$message .= "[B]" . \XF::phrase('trakt_tv_cast') . ":[/B] " . $this->tv_cast . "\r\n\r\n";
		}
		if ($this->tv_guest) {
			$message .= "[B]" . \XF::phrase('trakt_tv_guest_stars') . ":[/B] " . $this->tv_guest . "\r\n\r\n";
		}
		if ($this->tv_plot) {
			$message .= $this->tv_plot . "\r\n\r\n";
		}

		$message .= $this->message;

		return $message;
	}

	public function getEpisodeImageUrl($noposter = null, $canonical = true)
	{
		$app = $this->app();
		$options = $app->options();

		if ($this->tv_image) {
			$image = str_ireplace('/', '', $this->tv_image);
			if ($options->traktTvThreads_usecdn) {
				return "$options->traktTvThreads_cdn_path/tv/EpisodePosters/$this->post_id-$image";
			} elseif ($options->traktTvThreads_useLocalImages) {
				return $app->applyExternalDataUrl("tv/EpisodePosters/{$this->post_id}-{$image}", $canonical);
			}

			return "https://image.tmdb.org/t/p/w300/$image";
		} else {
			if ($options->traktTvThreads_usecdn) {
				return "$options->traktTvThreads_cdn_path/tv/EpisodePosters/no-poster.png";
			}

			return $app->applyExternalDataUrl("tv/EpisodePosters/no-poster.png", $canonical);
		}
	}

	public function getAbstractedImagePath()
	{
		if ($this->tv_image) {
			$episodeImage = str_ireplace('/', '', $this->tv_image);
			return sprintf('data://tv/EpisodePosters/%d-%s', $this->post_id, $episodeImage);
		}
		return null;
	}

	protected function _postDelete()
	{
		/** @var \nick97\TraktTV\Service\TVPost\Image $imageService */
		$imageService = $this->app()->service('nick97\TraktTV:TVPost\Image', $this);
		$imageService->deleteImageFile();

		$db = $this->db();
		$db->delete('nick97_trakt_tv_post', 'post_id = ?', $this->post_id);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_post';
		$structure->shortName = 'nick97\TraktTV:TVPost';
		$structure->contentType = 'tvpost';
		$structure->primaryKey = 'post_id';
		$structure->columns = [
			'post_id' => ['type' => self::UINT, 'default' => 0],
			'tv_id' => ['type' => self::UINT, 'default' => ''],
			'tv_season' => ['type' => self::UINT, 'default' => 0],
			'tv_episode' => ['type' => self::UINT, 'default' => 0],
			'tv_aired' => ['type' => self::STR, 'default' => ''],
			'tv_image' => ['type' => self::STR, 'default' => ''],
			'tv_title' => ['type' => self::STR, 'default' => ''],
			'tv_plot' => ['type' => self::STR, 'default' => ''],
			'tv_cast' => ['type' => self::STR, 'default' => ''],
			'tv_guest' => ['type' => self::STR, 'default' => ''],
			'message' => ['type' => self::STR, 'default' => ''],
			'tv_checked' => ['type' => self::UINT, 'default' => 0],
			'tv_poster' => ['type' => self::UINT, 'default' => 0],
		];

		$structure->relations = [
			'Post' => [
				'entity' => 'XF:Post',
				'type' => self::TO_ONE,
				'conditions' => 'post_id'
			],
		];

		$structure->getters['episode_image_url'] = true;

		$structure->options['apiResponse'] = [];

		return $structure;
	}
}
