<?php

namespace nick97\TraktTV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $person_id
 * @property string $profile_path
 * @property string $imdb_person_id
 * @property bool $adult
 * @property string $name
 * @property string $also_known_as
 * @property int $gender
 * @property string $biography
 * @property int $birthday
 * @property int $deathday
 * @property string $homepage
 * @property string $place_of_birth
 * @property string $known_for_department
 * @property int $small_image_date
 * @property int $large_image_date
 * @property float $popularity
 *
 * GETTERS
 * @property string|null $image_url
 */
class Person extends Entity
{
	public function setFromApiResponse(array $apiResponse)
	{
		$this->bulkSet([
			'birthday' => $apiResponse['birthday'] ?? 0,
			'known_for_department' => $apiResponse['known_for_department'] ?? '',
			'deathday' => $apiResponse['deathday'] ?? 0,
			'name' => $apiResponse['name'] ?? '',
			'also_known_as' => isset($apiResponse['also_known_as']) ? implode(',', $apiResponse['also_known_as']) : '',
			'gender' => $apiResponse['gender'] ?? 0,
			'biography' => $apiResponse['biography'] ?? '',
			'popularity' => $apiResponse['popularity'] ?? 0,
			'place_of_birth' => $apiResponse['place_of_birth'] ?? '',
			'profile_path' => $apiResponse['profile_path'] ?? '',
			'adult' => $apiResponse['adult'] ?? false,
			'imdb_person_id' => $apiResponse['imdb_id'] ?? false,
			'homepage' => $apiResponse['homepage'] ?? '',
		], ['forceConstraint' => true]);
	}

	public function canUpdate(&$error = null)
	{
		return \XF::visitor()->hasPermission('trakt_tvthreads_interface', 'updatePersons');
	}

	public function getImageUrl($sizeCode = 's', $canonical = true)
	{
		$app = $this->app();
		$options = $app->options();

		if ($this->profile_path) {
			$image = str_ireplace('/', '', $this->profile_path);

			if ($sizeCode == 's') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/SmallPersons/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/SmallPersons/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/w300_and_h450_bestv2/$image";
			} elseif ($sizeCode == 'l') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/LargePersons/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/LargePersons/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/w300_and_h450_bestv2/$image";
			}
		}

		return null;
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->profile_path) {
			$profileImage = str_ireplace('/', '', $this->profile_path);
			if ($sizeCode == 's') {
				return "data://tv/SmallPersons/$profileImage";
			} elseif ($sizeCode == 'l') {
				return "data://tv/LargePersons/$profileImage";
			}
		}
		return null;
	}

	protected function _postDelete()
	{
		if ($this->profile_path) {
			/** @var \nick97\TraktTV\Service\Person\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktTV:Person\Image', $this);
			$imageService->deleteImageFiles();
		}
	}

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_person';
		$structure->shortName = 'nick97\TraktTV:Person';
		$structure->primaryKey = 'person_id';
		$structure->columns = [
			'person_id' => ['type' => static::UINT, 'required' => true],
			'profile_path' => ['type' => static::STR, 'maxLength' => 150, 'default' => ''],
			'imdb_person_id' => ['type' => static::STR, 'maxLength' => 32, 'default' => ''],
			'adult' => ['type' => static::BOOL, 'default' => false],
			'name' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'also_known_as' => ['type' => static::STR, 'maxLength' => 65535, 'default' => '\'\''],
			'gender' => ['type' => static::UINT, 'maxLength' => 255, 'default' => '0'],
			'biography' => ['type' => static::STR, 'maxLength' => 65535, 'default' => '\'\''],
			'birthday' => ['type' => static::UINT, 'default' => '0'],
			'deathday' => ['type' => static::UINT, 'default' => '0'],
			'homepage' => ['type' => static::STR, 'maxLength' => 150, 'default' => ''],
			'place_of_birth' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'known_for_department' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'small_image_date' => ['type' => static::UINT, 'default' => 0],
			'large_image_date' => ['type' => static::UINT, 'default' => 0],
			'popularity' => ['type' => static::FLOAT, 'default' => '0.000'],
		];

		$structure->getters['image_url'] = true;

		return $structure;
	}
}
