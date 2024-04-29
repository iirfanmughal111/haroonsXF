<?php

namespace nick97\TraktMovies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $trakt_id
 * @property int $person_id
 * @property string $character
 * @property int $cast_id
 * @property string $credit_id
 * @property string $known_for_department
 * @property int $order
 *
 * RELATIONS
 * @property Movie $Movie
 * @property Person $Person
 */
class Cast extends Entity
{
	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_movies_cast';
		$structure->shortName = 'nick97\TraktMovies:Cast';
		$structure->primaryKey = ['trakt_id', 'person_id'];
		$structure->columns = [
			'trakt_id' => ['type' => static::UINT, 'required' => true],
			'person_id' => ['type' => static::UINT, 'required' => true],
			'character' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'cast_id' => ['type' => static::INT, 'default' => 0],
			'credit_id' => ['type' => static::STR, 'maxLength' => 24, 'required' => true],
			'known_for_department' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'order' => ['type' => static::UINT, 'default' => 0],
		];

		$structure->relations = [
			'Movie' => [
				'entity' => 'nick97\TraktMovies:Movie',
				'type' => self::TO_ONE,
				'conditions' => 'trakt_id',
				'primary' => true
			],
			'Person' => [
				'entity' => 'nick97\TraktMovies:Person',
				'type' => self::TO_ONE,
				'conditions' => 'person_id',
				'primary' => true
			],
		];

		return $structure;
	}
}