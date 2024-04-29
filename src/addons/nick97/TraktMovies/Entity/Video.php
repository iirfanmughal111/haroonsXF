<?php

namespace nick97\TraktMovies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $trakt_id
 * @property string $video_id
 * @property string $name
 * @property string $key
 * @property string $site
 * @property int $size
 * @property string $type
 * @property bool $official
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

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_movies_video';
		$structure->shortName = 'nick97\TraktMovies:Video';
		$structure->primaryKey = ['trakt_id', 'video_id'];
		$structure->columns = [
			'trakt_id' => ['type' => static::UINT, 'required' => true],
			'video_id' => ['type' => static::STR, 'maxLength' => 24, 'required' => true],
			'name' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'key' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
			'site' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
			'size' => ['type' => static::UINT, 'default' => '0'],
			'type' => ['type' => static::STR, 'maxLength' => 100, 'default' => ''],
			'official' => ['type' => static::BOOL, 'default' => false],
			'published_at' => ['type' => static::UINT, 'default' => \XF::$time],
			'iso_639_1' => ['type' => static::STR, 'maxLength' => 2, 'default' => ''],
			'iso_3166_1' => ['type' => static::STR, 'maxLength' => 2, 'default' => ''],
		];

		return $structure;
	}
}