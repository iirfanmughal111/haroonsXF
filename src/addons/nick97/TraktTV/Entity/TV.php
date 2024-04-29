<?php

namespace nick97\TraktTV\Entity;


use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $thread_id
 * @property string $tv_id
 * @property string $imdb_id
 * @property string $tv_image
 * @property string $backdrop_path
 * @property string $tv_genres
 * @property string $tv_director
 * @property string $tv_cast
 * @property string $tv_release
 * @property string $tv_title
 * @property int $tv_season
 * @property int $tv_episode
 * @property float $tv_rating
 * @property int $tv_votes
 * @property string $tv_plot
 * @property int $tv_thread
 * @property int $tv_checked
 * @property string $tv_trailer
 * @property string $comment
 * @property int $tv_poster
 * @property int $first_air_date
 * @property int $last_air_date
 * @property string $status
 * @property array $trakt_watch_providers
 * @property array|null $trakt_production_company_ids
 * @property array|null $trakt_network_ids
 * @property int $trakt_last_change_date
 *
 * RELATIONS
 * @property \nick97\TraktTV\XF\Entity\Thread $Thread
 * @property Rating[] $Ratings
 * @property Cast[] $Casts
 * @property Crew[] $Crews
 */
class TV extends Entity
{
	public function setFromApiResponse(array $apiResponse)
	{
		/** @var \nick97\TraktTV\Helper\Trakt\Show $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktTV:Trakt\Show');

		$this->bulkSet([
			'tv_id' => $apiResponse['id'] ?? 0,
			'imdb_id' => $apiResponse['external_ids']['imdb_id'] ?? '',
			'tv_title' => isset($apiResponse['name']) ? html_entity_decode($apiResponse['name']) : '',
			'tv_plot' => isset($apiResponse['overview']) ? html_entity_decode($apiResponse['overview']) : '',
			'tv_image' => $apiResponse['poster_path'] ?? '',
			'backdrop_path' => $apiResponse['backdrop_path'] ?? '',
			'tv_genres' => $traktHelper->getGenresList($apiResponse),
			'tv_director' => $traktHelper->getDirectorsList($apiResponse),
			'tv_cast' => $traktHelper->getCastList($apiResponse),
			'tv_release' => $apiResponse['first_air_date'] ?? '',
			'tv_trailer' => $traktHelper->getTrailer($apiResponse),
			'first_air_date' => isset($apiResponse['first_air_date']) ? strtotime($apiResponse['first_air_date']) : '',
			'last_air_date' => isset($apiResponse['last_air_date']) ? strtotime($apiResponse['last_air_date']) : '',
			'status' => $apiResponse['status'] ?? '',
			'trakt_watch_providers' => $traktHelper->getWatchProviders($apiResponse),
			'trakt_production_company_ids' => array_column($traktHelper->getProductionCompanies($apiResponse), 'id'),
			'trakt_network_ids' => array_column($traktHelper->getNetworks($apiResponse), 'id')
		], ['forceConstraint' => true]);
	}

	public function getExpectedThreadTitle($existingValues = false)
	{
		$title = $this->tv_title;

		if ($this->app()->options()->traktTvThreads_titleYear) {
			$year = '';
			if ($this->first_air_date) {
				$year = date('Y', $this->first_air_date);
			}

			$title .= " ($year)";
		}

		return $title;
	}

	public function getPostMessage()
	{
		$app = $this->app();
		$posterPath = $this->getImageUrl();

		$message = '[IMG]' . $posterPath . '[/IMG]' . "\r\n\r\n";
		$message .= '[B]' . \XF::phrase('title') . ':[/B] ' . $this->tv_title . "\r\n\r\n";
		if ($this->tv_genres) {
			$message .= '[B]' . \XF::phrase('trakt_tv_genre') . ':[/B] ' . $this->tv_genres . "\r\n\r\n";
		}
		if ($this->tv_director) {
			$message .= '[B]' . \XF::phrase('trakt_tv_creator') . ':[/B] ' . $this->tv_director . "\r\n\r\n";
		}
		if ($this->tv_cast) {
			$message .= '[B]' . \XF::phrase('trakt_tv_cast') . ':[/B] ' . $this->tv_cast . "\r\n\r\n";
		}
		if ($this->first_air_date) {
			$date = $app->templater()->func('date', [$this->first_air_date]);
			$message .= '[B]' . \XF::phrase('trakt_tv_first_aired') . ':[/B] ' . $date . "\r\n\r\n";
		}
		if ($this->last_air_date) {
			$date = $app->templater()->func('date', [$this->last_air_date]);
			$message .= '[B]' . \XF::phrase('trakt_tv_last_air_date') . ':[/B] ' . $date . "\r\n\r\n";
		}
		if ($this->tv_plot) {
			$message .= '[B]' . \XF::phrase('trakt_tv_overview') . ':[/B] ' . $this->tv_plot . "\r\n\r\n";
		}
		if ($this->tv_trailer) {
			$message .= '[MEDIA=youtube]' . $this->tv_trailer . '[/MEDIA]' . "\r\n\r\n";
		}

		if ($this->comment && !$app->options()->traktTvThreads_force_comments) {
			$message .= $this->comment;
		}

		return $message;
	}

	public function getWatchProviders()
	{
		$watchProviders = $this->trakt_watch_providers ?? [];
		$regionCodes = $this->app()->options()->traktTvThreads_watchProviderRegions;

		return array_filter($watchProviders, function ($country) use ($regionCodes) {
			return in_array($country, $regionCodes);
		}, ARRAY_FILTER_USE_KEY);
	}

	public function getWatchProviderCountries()
	{
		$availableCountries = array_keys($this->trakt_watch_providers);

		/** @var \nick97\TraktTV\Data\Country $countryData */
		$countryData = $this->app()->data('nick97\TraktTV:Country');
		$countryList = $countryData->getCountryOptions(true);

		return array_filter($countryList, function ($country) use ($availableCountries) {
			return in_array($country, $availableCountries);
		}, ARRAY_FILTER_USE_KEY);
	}

	public function hasRated(\XF\Entity\User $user = null)
	{
		if (!$user) {
			$user = \XF::visitor();
		}

		if (!$user->user_id) {
			return false;
		}

		return !empty($this->Ratings[$user->user_id]);
	}

	public function getImageUrl($sizeCode = 'l', $noposter = null, $canonical = true)
	{
		$app = $this->app();
		$options = $app->options();

		if ($this->tv_image) {
			$image = str_ireplace('/', '', $this->tv_image);
			if ($sizeCode == 'l') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/LargePosters/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/LargePosters/$this->thread_id-$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/$options->traktTvThreads_largePosterSize/$image";
			} elseif ($sizeCode == 's') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/SmallPosters$this->tv_image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/SmallPosters/$this->thread_id-$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/w342/$image";
			}
		} else {
			if ($sizeCode == 'l') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/LargePosters/no-poster.png";
				}
				return $app->applyExternalDataUrl("tv/LargePosters/no-poster.png", $canonical);
			} elseif ($sizeCode == 's') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/SmallPosters/no-poster.png";
				}
				return $app->applyExternalDataUrl("tv/SmallPosters/no-poster.png", $canonical);
			}
		}

		return '';
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->tv_image) {
			$image = str_ireplace('/', '', $this->tv_image);
			if ($sizeCode == 's') {
				return sprintf('data://tv/SmallPosters/%d-%s', $this->thread_id, $image);
			} elseif ($sizeCode == 'l') {
				return sprintf('data://tv/LargePosters/%d-%s', $this->thread_id, $image);
			}
		}
		return null;
	}

	public function getEpisodePosterUrl($noposter = null, $canonical = true)
	{
		$app = $this->app();
		$options = $app->options();

		if ($this->tv_season && $this->tv_image) {
			$image = str_ireplace('/', '', $this->tv_image);

			if ($options->traktTvThreads_usecdn) {
				return "$options->traktTvThreads_cdn_path/tv/EpisodePosters/$image";
			} elseif ($options->traktTvThreads_useLocalImages) {
				return $app->applyExternalDataUrl("tv/EpisodePosters/$image", $canonical);
			}

			return "https://image.tmdb.org/t/p/w300/$image";
		} else {
			if ($options->traktTvThreads_usecdn) {
				return "$options->traktTvThreads_cdn_path/tv/EpisodePosters/no-poster.png";
			}

			return $app->applyExternalDataUrl("tv/EpisodePosters/no-poster.png", $canonical);
		}
	}

	/**
	 * @param \XF\Api\Result\EntityResult $result
	 * @param $verbosity
	 * @param array $options
	 * @return void
	 *
	 * @api-out string $image_url
	 */
	protected function setupApiResultData(\XF\Api\Result\EntityResult $result, $verbosity = self::VERBOSITY_NORMAL, array $options = [])
	{
		$result->image_url = $this->tv_image;
	}

	public function rebuildRating($autoSave = false)
	{
		$rating = $this->db()->fetchRow("
			SELECT COUNT(*) AS total,
				SUM(rating) AS sum
			FROM nick97_trakt_tv_ratings
			WHERE thread_id = ?
		", [$this->thread_id]);

		$ratingSum = $rating['sum'] ?: 0;
		$total = $rating['total'] ?: 0;

		if ($ratingSum <= 0 || $total <= 0) {
			return;
		}

		$this->tv_rating = round(($ratingSum / $total), 2);

		if ($autoSave) {
			$this->save();
		}
	}

	protected function _postDelete()
	{
		if ($this->tv_image) {
			/** @var \nick97\TraktTV\Service\TV\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktTV:TV\Image', $this);
			$imageService->deleteImageFiles();
		}

		$db = $this->db();
		$db->delete('nick97_trakt_tv_ratings', 'thread_id = ?', $this->thread_id);
		$db->delete('nick97_trakt_tv_cast', 'tv_id = ?', $this->tv_id);
		$db->delete('nick97_trakt_tv_crew', 'tv_id = ?', $this->tv_id);
		$db->delete('nick97_trakt_tv_video', 'tv_id = ?', $this->tv_id);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_thread';
		$structure->shortName = 'nick97\TraktTV:TV';
		$structure->contentType = 'tv';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' => ['type' => self::UINT],
			'tv_id' => ['type' => self::UINT, 'required' => 'trakt_tv_error_id_not_valid', 'api' => true],
			'imdb_id' => ['type' => self::STR, 'default' => '', 'maxLength' => 32, 'api' => true],
			'tv_image' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'api' => true],
			'backdrop_path' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'api' => true],
			'tv_genres' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_director' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_cast' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_release' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_title' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true, 'api' => true],
			'tv_season' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tv_episode' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tv_rating' => ['type' => self::FLOAT, 'default' => 0, 'api' => true],
			'tv_votes' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tv_plot' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_thread' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tv_checked' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'tv_trailer' => ['type' => self::STR, 'default' => '', 'maxLength' => 255, 'api' => true],
			'comment' => ['type' => self::STR, 'default' => '', 'api' => true],
			'tv_poster' => ['type' => self::UINT, 'default' => 0, 'api' => true],
			'first_air_date' => ['type' => self::INT, 'default' => 0, 'api' => true],
			'last_air_date' => ['type' => self::INT, 'default' => 0, 'api' => true],
			'status' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true, 'api' => true],
			'trakt_watch_providers' => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
			'trakt_production_company_ids' => ['type' => self::JSON_ARRAY, 'default' => [], 'nullable' => true, 'api' => true],
			'trakt_network_ids' => ['type' => self::JSON_ARRAY, 'default' => [], 'nullable' => true, 'api' => true],
			'trakt_last_change_date' => ['type' => self::UINT, 'default' => \XF::$time],
		];

		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id'
			],
			'Ratings' => [
				'entity' => 'nick97\TraktTV:Rating',
				'type' => self::TO_MANY,
				'conditions' => 'thread_id',
				'key' => 'user_id',
				'primary' => false
			],
			'Casts' => [
				'entity' => 'nick97\TraktTV:Cast',
				'type' => self::TO_MANY,
				'conditions' => [
					['tv_id', '=', '$tv_id'],
					['tv_season', '=', '$tv_season'],
					['tv_episode', '=', '$tv_episode'],
				],
				'primary' => true,
				'key' => 'person_id'
			],
			'Crews' => [
				'entity' => 'nick97\TraktTV:Crew',
				'type' => self::TO_MANY,
				'conditions' => [
					['tv_id', '=', '$tv_id'],
					['tv_season', '=', '$tv_season'],
					['tv_episode', '=', '$tv_episode'],
				],
				'primary' => true,
				'key' => 'person_id'
			],
		];

		return $structure;
	}
}
