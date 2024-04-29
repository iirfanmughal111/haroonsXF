<?php

namespace nick97\TraktTV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $network_id
 * @property string $name
 * @property string $headquarters
 * @property string $homepage
 * @property string $logo_path
 * @property string $origin_country
 * @property int $small_image_date
 * @property int $large_image_date
 *
 * GETTERS
 * @property string|null $image_url
 */
class Network extends Entity
{
	public function setFromApiResponse(array $apiResponse)
	{
		$this->bulkSet([
			'headquarters' => $apiResponse['headquarters'] ?? '',
			'name' => $apiResponse['name'] ?? '',
			'origin_country' => $apiResponse['origin_country'] ?? '',
			'logo_path' => $apiResponse['logo_path'] ?? '',
			'homepage' => $apiResponse['homepage'] ?? '',
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
					return "$options->traktTvThreads_cdn_path/tv/SmallNetworks/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/SmallNetworks/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/$options->traktTvThreads_largeNetworkLogoSize/$image";
			} elseif ($sizeCode == 'l') {
				if ($options->traktTvThreads_usecdn) {
					return "$options->traktTvThreads_cdn_path/tv/LargeNetworks/$image";
				} elseif ($options->traktTvThreads_useLocalImages) {
					return $app->applyExternalDataUrl("tv/LargeNetworks/$image", $canonical);
				}

				return "https://image.tmdb.org/t/p/$options->traktTvThreads_largeNetworkLogoSize/$image";
			}
		}

		return null;
	}

	public function getAbstractedImagePath($sizeCode)
	{
		if ($this->logo_path) {
			$image = str_ireplace('/', '', $this->logo_path);
			if ($sizeCode == 's') {
				return "data://tv/SmallNetworks/$image";
			} elseif ($sizeCode == 'l') {
				return "data://tv/LargeNetworks/$image";
			}
		}
		return null;
	}

	protected function _postDelete()
	{
		if ($this->logo_path) {
			/** @var \nick97\TraktTV\Service\Network\Image $imageService */
			$imageService = $this->app()->service('nick97\TraktTV:Network\Image', $this);
			$imageService->deleteImageFiles();
		}
	}

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_network';
		$structure->shortName = 'nick97\TraktTV:Network';
		$structure->primaryKey = 'network_id';
		$structure->columns = [
			'network_id' => ['type' => static::UINT, 'required' => true],
			'name' => ['type' => static::STR, 'maxLength' => 255, 'required' => true],
			'headquarters' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'homepage' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'logo_path' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'origin_country' => ['type' => static::STR, 'maxLength' => 2, 'default' => ''],
			'small_image_date' => ['type' => static::UINT, 'default' => '0'],
			'large_image_date' => ['type' => static::UINT, 'default' => '0'],
		];

		$structure->getters['image_url'] = true;

		return $structure;
	}
}
