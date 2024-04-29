<?php

namespace Snog\Movies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $tmdb_id
 * @property int $person_id
 * @property string $credit_id
 * @property string $known_for_department
 * @property string $job
 * @property int $order
 *
 * RELATIONS
 * @property Movie $Movie
 * @property Person $Person
 */
class Crew extends Entity
{
	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_movies_crew';
		$structure->shortName = 'Snog\Movies:Crew';
		$structure->primaryKey = ['tmdb_id', 'person_id'];
		$structure->columns = [
			'tmdb_id' => ['type' => static::UINT, 'required' => true],
			'person_id' => ['type' => static::UINT, 'required' => true],
			'credit_id' => ['type' => static::STR, 'maxLength' => 24, 'required' => true],
			'known_for_department' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'department' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'job' => ['type' => static::STR, 'maxLength' => 255, 'default' => ''],
			'order' => ['type' => static::UINT, 'default' => 0],
		];

		$structure->relations = [
			'Movie' => [
				'entity' => 'Snog\Movies:Movie',
				'type' => self::TO_ONE,
				'conditions' => 'tmdb_id',
				'primary' => true
			],
			'Person' => [
				'entity' => 'Snog\Movies:Person',
				'type' => self::TO_ONE,
				'conditions' => 'person_id',
				'primary' => true
			],
		];

		return $structure;
	}
}