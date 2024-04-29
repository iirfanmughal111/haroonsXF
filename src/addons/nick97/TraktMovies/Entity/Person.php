<?php

namespace nick97\TraktMovies\Entity;

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
		return \XF::visitor()->hasPermission('trakt_movies', 'updatePersons');
	}

	public function getImageUrl($sizeCode = 's', $canonical = true)
	{
		$app = \XF::app();

		if ($this->profile_path)
		{
			if ($sizeCode == 's')
			{
				return $app->applyExternalDataUrl("movies/SmallPersons{$this->profile_path}", $canonical);
			}
			elseif ($sizeCode == 'l')
			{
				return $app->applyExternalDataUrl("movies/LargePersons{$this->profile_path}", $canonical);
			}
		}

		return null;
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->profile_path)
		{
			$image = str_ireplace('/', '', $this->profile_path);
			if ($sizeCode == 's')
			{
				return "data://movies/SmallPersons/$image";
			}
			elseif ($sizeCode == 'l')
			{
				return "data://movies/LargePersons/$image";
			}
		}
		return null;
	}

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_movies_person';
		$structure->shortName = 'nick97\TraktMovies:Person';
		$structure->primaryKey = 'person_id';
		$structure->columns = [
			'person_id' => ['type' => static::UINT, 'required' => true],
			'profile_path' => ['type' => static::STR, 'maxLength' => 150, 'default' => ''],
			'imdb_person_id' => ['type' => static::STR, 'maxLength' => 32, 'default' => ''],
			'adult' => ['type' => static::BOOL, 'default' => false],
			'name' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'also_known_as' => ['type' => static::STR, 'maxLength' => 65535, 'default' => ''],
			'gender' => ['type' => static::UINT, 'default' => '0'],
			'biography' => ['type' => static::STR, 'maxLength' => 65535, 'default' => ''],
			'birthday' => ['type' => static::UINT, 'default' => '0'],
			'deathday' => ['type' => static::UINT, 'default' => '0'],
			'homepage' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'place_of_birth' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'known_for_department' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'small_image_date' => ['type' => static::UINT, 'default' => 0],
			'large_image_date' => ['type' => static::UINT, 'default' => 0],
			'popularity' => ['type' => static::FLOAT, 'default' => '0.00'],
		];

		$structure->getters['image_url'] = true;

		return $structure;
	}
}