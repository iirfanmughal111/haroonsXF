<?php

namespace nick97\TraktMovies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\File;

/**
 * COLUMNS
 * @property int $thread_id
 * @property int $trakt_id
 * @property string $imdb_id
 * @property string $trakt_title
 * @property string $trakt_plot
 * @property string $trakt_image
 * @property string $backdrop_path
 * @property string $trakt_genres
 * @property string $trakt_director
 * @property string $trakt_cast
 * @property string $trakt_release
 * @property string $trakt_tagline
 * @property int $trakt_runtime
 * @property float $trakt_rating
 * @property int $trakt_votes
 * @property string $trakt_trailer
 * @property string $trakt_status
 * @property float $trakt_popularity
 * @property string $comment
 * @property array $trakt_watch_providers
 * @property array|null $trakt_production_company_ids
 * @property int $trakt_last_change_date
 *
 * RELATIONS
 * @property \XF\Entity\Thread $Thread
 * @property Rating[] $Ratings
 * @property Cast[] $Casts
 * @property Crew[] $Crews
 * @property Video[] $Videos
 */
class Movie extends Entity
{
	public function hasRated(\XF\Entity\User $user = null)
	{
		if (!$user)
		{
			$user = \XF::visitor();
		}

		if (!$user->user_id)
		{
			return false;
		}

		return !empty($this->Ratings[$user->user_id]);
	}

	public function getImageUrl($sizeCode = 'l', $noposter = null, $canonical = true)
	{
		$app = $this->app();
		$options = $app->options();

		$useLocal = $options->traktthreads_forum_local && !$options->traktthreads_usecdn;
		$useCdn = $options->traktthreads_usecdn && $options->traktthreads_cdn_path;

		if ($this->trakt_image)
		{
			if ($sizeCode == 'l')
			{
				if ($useLocal)
				{
					return $app->applyExternalDataUrl("movies/LargePosters{$this->trakt_image}", $canonical);
				}
				elseif ($useCdn)
				{
					return "$options->traktthreads_cdn_path/movies/LargePosters$this->trakt_image";
				}

				return "https://image.tmdb.org/t/p/$options->traktthreads_largePosterSize$this->trakt_image";
			}
			elseif ($sizeCode == 's')
			{
				if ($useLocal)
				{
					return $app->applyExternalDataUrl("movies/SmallPosters$this->trakt_image", $canonical);
				}
				elseif ($useCdn)
				{
					return "$options->traktthreads_cdn_path/movies/SmallPosters$this->trakt_image";
				}

				return "https://image.tmdb.org/t/p/$options->traktthreads_smallPosterSize$this->trakt_image";
			}
		}
		else
		{
			if ($sizeCode == 'l')
			{
				return $app->applyExternalDataUrl("movies/LargePosters/no-poster.jpg", $canonical);
			}
			elseif ($sizeCode == 's')
			{
				return $app->applyExternalDataUrl("movies/SmallPosters/no-poster.jpg", $canonical);
			}
		}

		return '';
	}

	public function getWatchProviders()
	{
		$watchProviders = $this->trakt_watch_providers ?? [];
		$regionCodes = $this->app()->options()->traktthreads_watchProviderRegions;

		return array_filter($watchProviders, function ($country) use ($regionCodes) {
			return in_array($country, $regionCodes);
		}, ARRAY_FILTER_USE_KEY);
	}

	public function getWatchProviderCountries()
	{
		$availableCountries = array_keys($this->trakt_watch_providers);

		/** @var \nick97\TraktMovies\Data\Country $countryData */
		$countryData = $this->app()->data('nick97\TraktMovies:Country');
		$countryList = $countryData->getCountryOptions(true);

		return array_filter($countryList, function ($country) use ($availableCountries) {
			return in_array($country, $availableCountries);
		}, ARRAY_FILTER_USE_KEY);
	}

	public function rebuildRating($autoSave = false)
	{
		$rating = $this->db()->fetchRow("
			SELECT COUNT(*) AS total,
				SUM(rating) AS sum
			FROM nick97_trakt_movies_ratings
			WHERE thread_id = ?
		", [$this->thread_id]);

		$ratingSum = $rating['sum'] ?: 0;
		$total = $rating['total'] ?: 0;

		if ($total > 0)
		{
			$this->trakt_rating = round(($ratingSum / $total), 2);
			$this->trakt_votes = $total;
		}
		else
		{
			$this->trakt_rating = 0;
			$this->trakt_votes = 0;
		}

		if ($autoSave)
		{
			$this->save();
			$this->updateChildMoviesRating($total, $ratingSum);
		}
	}

	public function updateChildMoviesRating($voteCount, $rating)
	{
		$this->db()->update('nick97_trakt_movies_thread', [
			'trakt_votes' => $voteCount,
			'trakt_rating' => $rating
		], 'trakt_id = ? AND thread_id > ?', [$this->trakt_id, $this->thread_id]);
	}

	public function setFromApiResponse(array $apiResponse)
	{
		/** @var \nick97\TraktMovies\Helper\Trakt $traktHelper */
		$traktHelper = \XF::helper('nick97\TraktMovies:Trakt');

		$this->bulkSet([
			'imdb_id' => $apiResponse['imdb_id'] ?? '',
			'trakt_title' => isset($apiResponse['title']) ? html_entity_decode($apiResponse['title']) : '',
			'trakt_plot' => isset($apiResponse['overview']) ? html_entity_decode($apiResponse['overview']) : '',
			'trakt_image' => $apiResponse['poster_path'] ?? '',
			'backdrop_path' => $apiResponse['backdrop_path'] ?? '',
			'trakt_genres' => $traktHelper->getGenresList($apiResponse),
			'trakt_director' => $traktHelper->getDirectorsList($apiResponse),
			'trakt_cast' => $traktHelper->getCastList($apiResponse),
			'trakt_release' => $apiResponse['release_date'] ?? '',
			'trakt_tagline' => isset($apiResponse['tagline']) ? html_entity_decode($apiResponse['tagline']) : '',
			'trakt_runtime' => $apiResponse['runtime'] ?? 0,
			'trakt_status' => $apiResponse['status'] ?? '',
			'trakt_trailer' => $traktHelper->getTrailer($apiResponse),
			'trakt_popularity' => $apiResponse['popularity'] ?? 0,
			'trakt_watch_providers' => $traktHelper->getMovieWatchProviders($apiResponse),
			'trakt_production_company_ids' => array_column($traktHelper->getMovieProductionCompanies($apiResponse), 'id')
		], ['forceConstraint' => true]);
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->trakt_image)
		{
			$image = str_ireplace('/', '', $this->trakt_image);
			if ($sizeCode == 's')
			{
				return "data://movies/SmallPosters/$image";
			}
			elseif ($sizeCode == 'l')
			{
				return "data://movies/LargePosters/$image";
			}
		}
		return null;
	}

	public function getExpectedThreadTitle($existingValues = false)
	{
		$title = $this->trakt_title;
		if (\XF::options()->traktthreads_useyear)
		{
			$year = '';
			$releaseExploded = [];
			if ($this->trakt_release)
			{
				$releaseExploded = explode('-', $this->trakt_release);
			}
			if (isset($releaseExploded[0]))
			{
				$year = ' (' . $releaseExploded[0] . ')';
			}
			$title .= $year;
		}

		return $title;
	}

	public function getPostMessage()
	{
		$posterPath = $this->getImageUrl();

		$message = '[IMG]' . $posterPath . '[/IMG]' . "\r\n\r\n";
		$message .= '[B]' . \XF::phrase('title') . ':[/B] ' . $this->trakt_title . "\r\n\r\n";
		if ($this->trakt_tagline)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_tagline') . ':[/B] ' . $this->trakt_tagline . "\r\n\r\n";
		}
		if ($this->trakt_genres)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_genre') . ':[/B] ' . $this->trakt_genres . "\r\n\r\n";
		}
		if ($this->trakt_director)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_director') . ':[/B] ' . $this->trakt_director . "\r\n\r\n";
		}
		if ($this->trakt_cast)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_cast') . ':[/B] ' . $this->trakt_cast . "\r\n\r\n";
		}
		if ($this->trakt_status)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_status') . ':[/B] ' . $this->trakt_status . "\r\n\r\n";
		}
		if ($this->trakt_release)
		{
			$date = $this->app()->templater()->func('date', [strtotime($this->trakt_release)]);
			$message .= '[B]' . \XF::phrase('trakt_movies_release') . ':[/B] ' . $date . "\r\n\r\n";
		}
		if ($this->trakt_runtime)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_runtime') . ':[/B] ' . $this->trakt_runtime . "\r\n\r\n";
		}
		if ($this->trakt_plot)
		{
			$message .= '[B]' . \XF::phrase('trakt_movies_plot') . ":[/B] " . $this->trakt_plot . "\r\n\r\n";
		}
		if ($this->trakt_trailer)
		{
			$message .= '[MEDIA=youtube]' . $this->trakt_trailer . '[/MEDIA]' . "\r\n\r\n";
		}

		return $message;
	}

	protected function _postSave()
	{
		if ($this->isUpdate())
		{
			$oldPoster = $this->getPreviousValue('trakt_image');
			if (!empty($oldPoster) && $oldPoster != $this->trakt_image)
			{
				$path = sprintf('data://movies/LargePosters%s', $oldPoster);
				File::deleteFromAbstractedPath($path);

				$path = sprintf('data://movies/SmallPosters%s', $oldPoster);
				File::deleteFromAbstractedPath($path);
			}
		}
	}

	protected function _postDelete()
	{
		$movieId = $this->trakt_id;
		$image = $this->trakt_image;

		if ($image)
		{
			/** @var \nick97\TraktMovies\Service\Movie\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktMovies:Movie\Image', $this);
			$imageService->deleteImageFiles();
		}

		$db = $this->db();

		// Delete all linked movies
		$db->delete('nick97_trakt_movies_thread', 'trakt_id = ?', $movieId);
		$db->delete('nick97_trakt_movies_cast', 'trakt_id = ?', $movieId);
		$db->delete('nick97_trakt_movies_crew', 'trakt_id = ?', $movieId);
		$db->delete('nick97_trakt_movies_video', 'trakt_id = ?', $movieId);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_movies_thread';
		$structure->shortName = 'nick97\TraktMovies:Movie';
		$structure->contentType = 'movie';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' => ['type' => self::UINT],
			'trakt_id' => ['type' => self::UINT, 'required' => 'trakt_movies_error_not_valid'],
			'imdb_id' => ['type' => self::STR, 'default' => '', 'maxLength' => 32, 'forced' => true],
			'trakt_title' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'trakt_plot' => ['type' => self::STR, 'default' => ''],
			'trakt_image' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'backdrop_path'  => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'trakt_genres' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'trakt_director' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'trakt_cast' => ['type' => self::STR, 'default' => ''],
			'trakt_release' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'trakt_tagline' => ['type' => self::STR, 'default' => ''],
			'trakt_runtime' => ['type' => self::UINT, 'default' => 0],
			'trakt_rating' => ['type' => self::FLOAT, 'default' => 0],
			'trakt_votes' => ['type' => self::UINT, 'default' => 0],
			'trakt_trailer' => ['type' => self::STR, 'default' => '', 'maxLength' => 255, 'forced' => true],
			'trakt_status' => ['type' => self::STR, 'default' => 0, 'maxLength' => 150, 'forced' => true],
			'trakt_popularity' => ['type' => self::FLOAT, 'default' => 0],
			'comment' => ['type' => self::STR, 'default' => ''],
			'trakt_watch_providers' => ['type' => self::JSON_ARRAY, 'default' => []],
			'trakt_production_company_ids' => ['type' => self::JSON_ARRAY, 'default' => [], 'nullable' => true],
			'trakt_last_change_date' => ['type' => self::UINT, 'default' => 0],
		];

		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true
			],
			'Ratings' => [
				'entity' => 'nick97\TraktMovies:Rating',
				'type' => self::TO_MANY,
				'conditions' => 'thread_id',
				'key' => 'user_id',
				'primary' => true
			],
			'Casts' => [
				'entity' => 'nick97\TraktMovies:Cast',
				'type' => self::TO_MANY,
				'conditions' => 'trakt_id',
				'primary' => true,
				'key' => 'person_id'
			],
			'Crews' => [
				'entity' => 'nick97\TraktMovies:Crew',
				'type' => self::TO_MANY,
				'conditions' => 'trakt_id',
				'primary' => true,
				'key' => 'person_id'
			],
			'Videos' => [
				'entity' => 'nick97\TraktMovies:Video',
				'type' => self::TO_MANY,
				'conditions' => 'trakt_id',
				'primary' => true,
				'key' => 'key'
			],
		];

		return $structure;
	}
}