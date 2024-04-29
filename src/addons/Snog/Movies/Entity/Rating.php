<?php

namespace Snog\Movies\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $rating_id
 * @property int $thread_id
 * @property int $user_id
 * @property int $rating
 *
 * RELATIONS
 * @property Movie $Movie
 * @property \XF\Entity\Thread $Thread
 */
class Rating extends Entity
{
	protected function _postSave()
	{
		$this->ratingAdded();
	}

	protected function _postDelete()
	{
		$this->ratingRemoved();
	}

	protected function ratingAdded()
	{
		$movie = $this->Movie;
		if ($movie)
		{
			$movie->rebuildRating(true);
		}
	}

	protected function ratingRemoved()
	{
		$movie = $this->Movie;
		if ($movie)
		{
			$movie->rebuildRating(true);
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_movies_ratings';
		$structure->shortName = 'Snog\Movies:Rating';
		$structure->contentType = 'rating';
		$structure->primaryKey = 'rating_id';
		$structure->columns = [
			'rating_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'thread_id' => ['type' => self::UINT],
			'user_id' => ['type' => self::UINT],
			'rating' => ['type' => self::UINT],
		];

		$structure->relations = [
			'Movie' => [
				'entity' => 'Snog\Movies:Movie',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id'
			],
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true
			],
		];

		return $structure;
	}
}
