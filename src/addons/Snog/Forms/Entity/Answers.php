<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * Class Answers
 * @package Snog\Forms\Entity
 *
 * COLUMNS
 * @property int $answer_id
 * @property int $log_id
 * @property int $posid
 * @property int $questionid
 * @property int $answer_date
 * @property int $user_id
 * @property string $answer
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property Log $Log
 * @property Form $Form
 * @property Question $Question
 */
class Answers extends Entity implements ExportableInterface
{
	public function getExportData(): array
	{
		return [
			'answer_id' => $this->answer_id,
			'posid' => $this->posid,
			'questionid' => $this->questionid,
			'answer_date' => $this->answer_date,
			'user_id' => $this->user_id,
			'answer' => htmlspecialchars($this->answer),
		];
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_answers';
		$structure->shortName = 'Snog\Forms:Answers';
		$structure->contentType = 'answer';
		$structure->primaryKey = 'answer_id';
		$structure->columns = [
			'answer_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'log_id' => ['type' => self::UINT, 'default' => 0],
			'posid' => ['type' => self::UINT, 'default' => 0],
			'questionid' => ['type' => self::UINT, 'default' => 0],
			'answer_date' => ['type' => self::UINT, 'default' => 0],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'answer' => ['type' => self::STR, 'default' => ''],
		];

		$structure->relations = [
			'Log' => [
				'entity' => 'Snog\Forms:Log',
				'type' => self::TO_ONE,
				'conditions' => 'log_id',
				'primary' => true
			],
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => self::TO_ONE,
				'conditions' => 'posid',
				'primary' => true
			],
			'Question' => [
				'entity' => 'Snog\Forms:Question',
				'type' => self::TO_ONE,
				'conditions' => 'questionid',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true,
			]
		];

		return $structure;
	}
}