<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * Class Promotion
 * @package Snog\Forms\Entity
 *
 * COLUMNS
 * @property int $post_id
 * @property int $thread_id
 * @property int $poll_id
 * @property int $user_id
 * @property int $posid
 * @property int $close_date
 * @property bool $approve
 * @property int $original_group
 * @property array $original_additional
 * @property int $new_group
 * @property array $new_additional
 * @property int $forum_node
 *
 * RELATIONS
 * @property Form $Form
 * @property \XF\Entity\Thread $Thread
 * @property \XF\Entity\Poll $closedPoll
 */
class Promotion extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_promotions';
		$structure->shortName = 'Snog\Forms:Promotion';
		$structure->contentType = 'promotion';
		$structure->primaryKey = 'post_id';
		$structure->columns = [
			'post_id' => ['type' => self::UINT, 'default' => 0],
			'thread_id' => ['type' => self::UINT, 'default' => 0],
			'poll_id' => ['type' => self::UINT, 'default' => 0],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'posid' => ['type' => self::UINT, 'default' => 0],
			'close_date' => ['type' => self::UINT, 'default' => 0],
			'approve' => ['type' => self::BOOL, 'default' => false],
			'original_group' => ['type' => self::UINT, 'default' => 0],
			'original_additional' => ['type' => self::JSON_ARRAY, 'default' => []],
			'new_group' => ['type' => self::UINT, 'default' => 0],
			'new_additional' => ['type' => self::JSON_ARRAY, 'default' => []],
			'forum_node' => ['type' => self::UINT, 'default' => 0],
		];

		$structure->getters = [];
		$structure->relations = [
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => self::TO_ONE,
				'conditions' => [['posid', '=', '$posid']],
				'primary' => true
			],
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true,
			],
			'closedPoll' => [
				'entity' => 'XF:Poll',
				'type' => self::TO_ONE,
				'conditions' => [
					['poll_id', '=', '$poll_id'],
					['close_date', '=', '$close_date']
				],
			],
		];

		return $structure;
	}
}