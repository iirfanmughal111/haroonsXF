<?php

namespace nick97\TraktTV\Entity;

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
		$app = $this->app();
		$options = $app->options();

		if ($this->logo_path) {
			$image = str_ireplace('/', '', $this->logo_path);

			if ($sizeCode == 's') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/SmallCompanies/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/SmallCompanies/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/$options->traktTvThreads_largeCompanyLogoSize/$image";
			} elseif ($sizeCode == 'l') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/LargeCompanies/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/LargeCompanies/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/$options->traktTvThreads_largeCompanyLogoSize/$image";
			}
		}

		return null;
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->logo_path) {
			$image = str_ireplace('/', '', $this->logo_path);
			if ($sizeCode == 's') {
				return "data://tv/SmallCompanies/$image";
			} elseif ($sizeCode == 'l') {
				return "data://tv/LargeCompanies/$image";
			}
		}
		return null;
	}

	protected function _postDelete()
	{
		if ($this->logo_path) {
			/** @var \nick97\TraktTV\Service\Company\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktTV:Company\Image', $this);
			$imageService->deleteImageFiles();
		}
	}

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_company';
		$structure->shortName = 'nick97\TraktTV:Company';
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
				'entity' => 'nick97\TraktTV:Company',
				'type' => self::TO_ONE,
				'conditions' => [['company_id', '=', 'parent_company_id']],
				'primary' => true
			],
		];

		return $structure;
	}
}
