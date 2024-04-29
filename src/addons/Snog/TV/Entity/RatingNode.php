<?php

namespace Snog\TV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $rating_id
 * @property int $node_id
 * @property int $user_id
 * @property int $rating
 *
 * RELATIONS
 * @property TVForum $TVForum
 */
class RatingNode extends Entity
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
		$forum = $this->TVForum;
		if ($forum)
		{
			if ($this->isInsert())
			{
				$forum->tv_votes += 1;
			}

			$forum->rebuildRating();
			$forum->save();
		}
	}

	protected function ratingRemoved()
	{
		$forum = $this->TVForum;
		if ($forum)
		{
			$forum->tv_votes -= 1;

			$forum->rebuildRating();
			$forum->save();
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_tv_ratings_node';
		$structure->shortName = 'Snog\TV:RatingNode';
		$structure->contentType = 'rating';
		$structure->primaryKey = 'rating_id';
		$structure->columns = [
			'rating_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'node_id' => ['type' => self::UINT],
			'user_id' => ['type' => self::UINT],
			'rating' => ['type' => self::UINT],
		];

		$structure->relations = [
			'TVForum' => [
				'entity' => 'Snog\TV:TVForum',
				'type' => self::TO_ONE,
				'conditions' => 'node_id'
			]
		];

		return $structure;
	}
}
