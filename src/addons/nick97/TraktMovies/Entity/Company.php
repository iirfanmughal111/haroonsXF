<?php

namespace nick97\TraktMovies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $company_id
 * @property string $name
 * @property string $description
 * @property string $headquarters
 * @property string $homepage
 * @property string $logo_path
 * @property string $origin_country
 * @property int $parent_company_id
 * @property int $small_image_date
 * @property int $large_image_date
 *
 * GETTERS
 * @property string|null $image_url
 *
 * RELATIONS
 * @property Company|null $ParentCompany
 */
class Company extends Entity
{
	public function setFromApiResponse(array $apiResponse)
	{
		$this->bulkSet([
			'description' => $apiResponse['description'] ?? '',
			'headquarters' => $apiResponse['headquarters'] ?? '',
			'name' => $apiResponse['name'] ?? '',
			'origin_country' => $apiResponse['origin_country'] ?? '',
			'logo_path' => $apiResponse['logo_path'] ?? '',
			'homepage' => $apiResponse['homepage'] ?? '',
			'parent_company_id' => !empty($apiResponse['parent_company']) ? $apiResponse['parent_company']['id'] : '',
		], ['forceConstraint' => true]);
	}

	public function getImageUrl($sizeCode = 's', $canonical = true)
	{
		$app = \XF::app();

		if ($this->logo_path)
		{
			if ($sizeCode == 's')
			{
				return $app->applyExternalDataUrl("movies/SmallCompanies{$this->logo_path}", $canonical);
			}
			elseif ($sizeCode == 'l')
			{
				return $app->applyExternalDataUrl("movies/LargeCompanies{$this->logo_path}", $canonical);
			}
		}

		return null;
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->logo_path)
		{
			$image = str_ireplace('/', '', $this->logo_path);
			if ($sizeCode == 's')
			{
				return "data://movies/SmallCompanies/$image";
			}
			elseif ($sizeCode == 'l')
			{
				return "data://movies/LargeCompanies/$image";
			}
		}
		return null;
	}

	protected function _postDelete()
	{
		if ($this->logo_path)
		{
			/** @var \nick97\TraktMovies\Service\Company\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktMovies:Company\Image', $this);
			$imageService->deleteImageFiles();
		}
	}

    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'nick97_trakt_movies_company';
        $structure->shortName = 'nick97\TraktMovies:Company';
        $structure->primaryKey = 'company_id';
        $structure->columns = [
            'company_id' => ['type' => static::UINT, 'required' => true],
            'name' => ['type' => static::STR, 'maxLength' => 255, 'required' => true],
            'description' => ['type' => static::STR, 'maxLength' => 65535, 'default' => ''],
            'headquarters' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
            'homepage' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
            'logo_path' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
            'origin_country' => ['type' => static::STR, 'maxLength' => 2, 'default' => ''],
            'parent_company_id' => ['type' => static::UINT, 'default' => '0'],
			'small_image_date' => ['type' => static::UINT, 'default' => 0],
			'large_image_date' => ['type' => static::UINT, 'default' => 0],
        ];

		$structure->getters['image_url'] = true;

		$structure->relations = [
			'ParentCompany' => [
				'entity' => 'nick97\TraktMovies:Company',
				'type' => self::TO_ONE,
				'conditions' => [['company_id', '=', 'parent_company_id']],
				'primary' => true
			],
		];

		return $structure;
    }
}