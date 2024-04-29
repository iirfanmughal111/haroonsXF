<?php

namespace Snog\Movies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\File;

/**
 * COLUMNS
 * @property int $thread_id
 * @property int $tmdb_id
 * @property string $imdb_id
 * @property string $tmdb_title
 * @property string $tmdb_plot
 * @property string $tmdb_image
 * @property string $backdrop_path
 * @property string $tmdb_genres
 * @property string $tmdb_director
 * @property string $tmdb_cast
 * @property string $tmdb_release
 * @property string $tmdb_tagline
 * @property int $tmdb_runtime
 * @property float $tmdb_rating
 * @property int $tmdb_votes
 * @property string $tmdb_trailer
 * @property string $tmdb_status
 * @property float $tmdb_popularity
 * @property string $comment
 * @property array $tmdb_watch_providers
 * @property array|null $tmdb_production_company_ids
 * @property int $tmdb_last_change_date
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

		$useLocal = $options->tmdbthreads_forum_local && !$options->tmdbthreads_usecdn;
		$useCdn = $options->tmdbthreads_usecdn && $options->tmdbthreads_cdn_path;

		if ($this->tmdb_image)
		{
			if ($sizeCode == 'l')
			{
				if ($useLocal)
				{
					return $app->applyExternalDataUrl("movies/LargePosters{$this->tmdb_image}", $canonical);
				}
				elseif ($useCdn)
				{
					return "$options->tmdbthreads_cdn_path/movies/LargePosters$this->tmdb_image";
				}

				return "https://image.tmdb.org/t/p/$options->tmdbthreads_largePosterSize$this->tmdb_image";
			}
			elseif ($sizeCode == 's')
			{
				if ($useLocal)
				{
					return $app->applyExternalDataUrl("movies/SmallPosters$this->tmdb_image", $canonical);
				}
				elseif ($useCdn)
				{
					return "$options->tmdbthreads_cdn_path/movies/SmallPosters$this->tmdb_image";
				}

				return "https://image.tmdb.org/t/p/$options->tmdbthreads_smallPosterSize$this->tmdb_image";
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
		$watchProviders = $this->tmdb_watch_providers ?? [];
		$regionCodes = $this->app()->options()->tmdbthreads_watchProviderRegions;

		return array_filter($watchProviders, function ($country) use ($regionCodes) {
			return in_array($country, $regionCodes);
		}, ARRAY_FILTER_USE_KEY);
	}

	public function getWatchProviderCountries()
	{
		$availableCountries = array_keys($this->tmdb_watch_providers);

		/** @var \Snog\Movies\Data\Country $countryData */
		$countryData = $this->app()->data('Snog\Movies:Country');
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
			FROM xf_snog_movies_ratings
			WHERE thread_id = ?
		", [$this->thread_id]);

		$ratingSum = $rating['sum'] ?: 0;
		$total = $rating['total'] ?: 0;

		if ($total > 0)
		{
			$this->tmdb_rating = round(($ratingSum / $total), 2);
			$this->tmdb_votes = $total;
		}
		else
		{
			$this->tmdb_rating = 0;
			$this->tmdb_votes = 0;
		}

		if ($autoSave)
		{
			$this->save();
			$this->updateChildMoviesRating($total, $ratingSum);
		}
	}

	public function updateChildMoviesRating($voteCount, $rating)
	{
		$this->db()->update('xf_snog_movies_thread', [
			'tmdb_votes' => $voteCount,
			'tmdb_rating' => $rating
		], 'tmdb_id = ? AND thread_id > ?', [$this->tmdb_id, $this->thread_id]);
	}

	public function setFromApiResponse(array $apiResponse)
	{
		/** @var \Snog\Movies\Helper\Tmdb $tmdbHelper */
		$tmdbHelper = \XF::helper('Snog\Movies:Tmdb');

		$this->bulkSet([
			'imdb_id' => $apiResponse['imdb_id'] ?? '',
			'tmdb_title' => isset($apiResponse['title']) ? html_entity_decode($apiResponse['title']) : '',
			'tmdb_plot' => isset($apiResponse['overview']) ? html_entity_decode($apiResponse['overview']) : '',
			'tmdb_image' => $apiResponse['poster_path'] ?? '',
			'backdrop_path' => $apiResponse['backdrop_path'] ?? '',
			'tmdb_genres' => $tmdbHelper->getGenresList($apiResponse),
			'tmdb_director' => $tmdbHelper->getDirectorsList($apiResponse),
			'tmdb_cast' => $tmdbHelper->getCastList($apiResponse),
			'tmdb_release' => $apiResponse['release_date'] ?? '',
			'tmdb_tagline' => isset($apiResponse['tagline']) ? html_entity_decode($apiResponse['tagline']) : '',
			'tmdb_runtime' => $apiResponse['runtime'] ?? 0,
			'tmdb_status' => $apiResponse['status'] ?? '',
			'tmdb_trailer' => $tmdbHelper->getTrailer($apiResponse),
			'tmdb_popularity' => $apiResponse['popularity'] ?? 0,
			'tmdb_watch_providers' => $tmdbHelper->getMovieWatchProviders($apiResponse),
			'tmdb_production_company_ids' => array_column($tmdbHelper->getMovieProductionCompanies($apiResponse), 'id')
		], ['forceConstraint' => true]);
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->tmdb_image)
		{
			$image = str_ireplace('/', '', $this->tmdb_image);
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
		$title = $this->tmdb_title;
		if (\XF::options()->tmdbthreads_useyear)
		{
			$year = '';
			$releaseExploded = [];
			if ($this->tmdb_release)
			{
				$releaseExploded = explode('-', $this->tmdb_release);
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
		$message .= '[B]' . \XF::phrase('title') . ':[/B] ' . $this->tmdb_title . "\r\n\r\n";
		if ($this->tmdb_tagline)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_tagline') . ':[/B] ' . $this->tmdb_tagline . "\r\n\r\n";
		}
		if ($this->tmdb_genres)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_genre') . ':[/B] ' . $this->tmdb_genres . "\r\n\r\n";
		}
		if ($this->tmdb_director)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_director') . ':[/B] ' . $this->tmdb_director . "\r\n\r\n";
		}
		if ($this->tmdb_cast)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_cast') . ':[/B] ' . $this->tmdb_cast . "\r\n\r\n";
		}
		if ($this->tmdb_status)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_status') . ':[/B] ' . $this->tmdb_status . "\r\n\r\n";
		}
		if ($this->tmdb_release)
		{
			$date = $this->app()->templater()->func('date', [strtotime($this->tmdb_release)]);
			$message .= '[B]' . \XF::phrase('snog_movies_release') . ':[/B] ' . $date . "\r\n\r\n";
		}
		if ($this->tmdb_runtime)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_runtime') . ':[/B] ' . $this->tmdb_runtime . "\r\n\r\n";
		}
		if ($this->tmdb_plot)
		{
			$message .= '[B]' . \XF::phrase('snog_movies_plot') . ":[/B] " . $this->tmdb_plot . "\r\n\r\n";
		}
		if ($this->tmdb_trailer)
		{
			$message .= '[MEDIA=youtube]' . $this->tmdb_trailer . '[/MEDIA]' . "\r\n\r\n";
		}

		return $message;
	}

	protected function _postSave()
	{
		if ($this->isUpdate())
		{
			$oldPoster = $this->getPreviousValue('tmdb_image');
			if (!empty($oldPoster) && $oldPoster != $this->tmdb_image)
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
		$movieId = $this->tmdb_id;
		$image = $this->tmdb_image;

		if ($image)
		{
			/** @var \Snog\Movies\Service\Movie\Image $imageService */
			$imageService = $this->app()->service('Snog\Movies:Movie\Image', $this);
			$imageService->deleteImageFiles();
		}

		$db = $this->db();

		// Delete all linked movies
		$db->delete('xf_snog_movies_thread', 'tmdb_id = ?', $movieId);
		$db->delete('xf_snog_movies_cast', 'tmdb_id = ?', $movieId);
		$db->delete('xf_snog_movies_crew', 'tmdb_id = ?', $movieId);
		$db->delete('xf_snog_movies_video', 'tmdb_id = ?', $movieId);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_movies_thread';
		$structure->shortName = 'Snog\Movies:Movie';
		$structure->contentType = 'movie';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' => ['type' => self::UINT],
			'tmdb_id' => ['type' => self::UINT, 'required' => 'snog_movies_error_not_valid'],
			'imdb_id' => ['type' => self::STR, 'default' => '', 'maxLength' => 32, 'forced' => true],
			'tmdb_title' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'tmdb_plot' => ['type' => self::STR, 'default' => ''],
			'tmdb_image' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'backdrop_path'  => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'tmdb_genres' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'tmdb_director' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'tmdb_cast' => ['type' => self::STR, 'default' => ''],
			'tmdb_release' => ['type' => self::STR, 'default' => '', 'maxLength' => 150, 'forced' => true],
			'tmdb_tagline' => ['type' => self::STR, 'default' => ''],
			'tmdb_runtime' => ['type' => self::UINT, 'default' => 0],
			'tmdb_rating' => ['type' => self::FLOAT, 'default' => 0],
			'tmdb_votes' => ['type' => self::UINT, 'default' => 0],
			'tmdb_trailer' => ['type' => self::STR, 'default' => '', 'maxLength' => 255, 'forced' => true],
			'tmdb_status' => ['type' => self::STR, 'default' => 0, 'maxLength' => 150, 'forced' => true],
			'tmdb_popularity' => ['type' => self::FLOAT, 'default' => 0],
			'comment' => ['type' => self::STR, 'default' => ''],
			'tmdb_watch_providers' => ['type' => self::JSON_ARRAY, 'default' => []],
			'tmdb_production_company_ids' => ['type' => self::JSON_ARRAY, 'default' => [], 'nullable' => true],
			'tmdb_last_change_date' => ['type' => self::UINT, 'default' => 0],
		];

		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true
			],
			'Ratings' => [
				'entity' => 'Snog\Movies:Rating',
				'type' => self::TO_MANY,
				'conditions' => 'thread_id',
				'key' => 'user_id',
				'primary' => true
			],
			'Casts' => [
				'entity' => 'Snog\Movies:Cast',
				'type' => self::TO_MANY,
				'conditions' => 'tmdb_id',
				'primary' => true,
				'key' => 'person_id'
			],
			'Crews' => [
				'entity' => 'Snog\Movies:Crew',
				'type' => self::TO_MANY,
				'conditions' => 'tmdb_id',
				'primary' => true,
				'key' => 'person_id'
			],
			'Videos' => [
				'entity' => 'Snog\Movies:Video',
				'type' => self::TO_MANY,
				'conditions' => 'tmdb_id',
				'primary' => true,
				'key' => 'key'
			],
		];

		return $structure;
	}
}