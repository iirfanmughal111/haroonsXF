<?php

namespace Snog\TV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $tv_id
 * @property int $tv_season
 * @property int $tv_episode
 * @property string $video_id
 * @property string $name
 * @property string $key
 * @property string $site
 * @property int $size
 * @property string $type
 * @property int $official
 * @property int $published_at
 * @property string $iso_639_1
 * @property string $iso_3166_1
 */
class Video extends Entity
{
	public function getVideoUrl()
	{
		if ($this->site == 'YouTube')
		{
			return "https://www.youtube.com/watch?v=$this->key";
		}
		return '';
	}

	public function setFromApiResponse(array $apiResponse)
	{
		$this->bulkSet([
			'video_id' => $apiResponse['id'] ?? '',
			'name' => $apiResponse['name'] ?? '',
			'key' => $apiResponse['key'] ?? '',
			'site' => $apiResponse['site'] ?? '',
			'size' => $apiResponse['size'] ?? 0,
			'type' => $apiResponse['type'] ?? '',
			'official' => $apiResponse['official'] ?? 0,
			'published_at' => isset($apiResponse['published_at']) ? strtotime($apiResponse['published_at']) : 0,
			'iso_639_1' => $apiResponse['iso_639_1'] ?? '',
			'iso_3166_1' => $apiResponse['iso_3166_1'] ?? '',
		]);
	}

    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_snog_tv_video';
        $structure->shortName = 'Snog\TV:Video';
        $structure->primaryKey = ['tv_id', 'tv_season', 'tv_episode', 'video_id'];
        $structure->columns = [
            'tv_id' => ['type' => static::UINT, 'required' => true],
            'tv_season' => ['type' => static::UINT, 'default' => 0],
            'tv_episode' => ['type' => static::UINT, 'default' => 0],
            'video_id' => ['type' => static::STR, 'maxLength' => 24, 'required' => true],
            'name' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
            'key' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
            'site' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
            'size' => ['type' => static::UINT, 'default' => 0],
            'type' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
            'official' => ['type' => static::UINT, 'maxLength' => 255, 'default' => 0],
            'published_at' => ['type' => static::UINT, 'default' => 0],
            'iso_639_1' => ['type' => static::STR, 'maxLength' => 2, 'required' => true],
            'iso_3166_1' => ['type' => static::STR, 'maxLength' => 2, 'required' => true],
        ];

        return $structure;
    }
}