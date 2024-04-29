<?php

namespace nick97\TraktTV\Entity;

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
 * @property TV $TV
 * @property \nick97\TraktTV\XF\Entity\Thread $Thread
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
		$show = $this->traktTV;
		if ($show) {
			if ($this->isInsert()) {
				$show->tv_votes += 1;
			}

			$show->rebuildRating();
			$show->save();
		}
	}

	protected function ratingRemoved()
	{
		$show = $this->traktTV;
		if ($show) {
			$show->tv_votes -= 1;

			$show->rebuildRating();
			$show->save();
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'nick97_trakt_tv_ratings';
		$structure->shortName = 'nick97\TraktTV:Rating';
		$structure->contentType = 'rating';
		$structure->primaryKey = 'rating_id';
		$structure->columns = [
			'rating_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'thread_id' => ['type' => self::UINT],
			'user_id' => ['type' => self::UINT],
			'rating' => ['type' => self::UINT],
		];

		$structure->relations = [
			'TV' => [
				'entity' => 'nick97\TraktTV:TV',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id'
			],
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id'
			],
		];

		return $structure;
	}
}
