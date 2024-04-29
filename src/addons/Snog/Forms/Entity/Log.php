<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int log_id
 * @property int form_id
 * @property int user_id
 * @property string ip_address
 * @property int log_date
 *
 * RELATIONS
 * @property Form Form
 * @property \XF\Entity\User User
 */
class Log extends Entity implements ExportableInterface
{
	protected function _postDelete()
	{
		if ($this->log_id)
		{
			$this->db()->delete('xf_snog_forms_answers', 'log_id = ?', $this->log_id);
		}
	}

	public function getExportData(): array
	{
		return [
			'log_id' => $this->log_id,
			'form_id' => $this->form_id,
			'user_id' => $this->user_id,
			'ip_address' => \XF\Util\Ip::convertIpBinaryToString($this->ip_address),
			'log_date' => $this->log_date,
		];
	}

	/**
	 * @param Structure $structure
	 * @return Structure
	 */
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_logs';
		$structure->shortName = 'Snog\Forms:Log';
		$structure->primaryKey = 'log_id';
		$structure->columns = [
			'log_id' => ['type' => static::UINT, 'autoIncrement' => true],
			'form_id' => ['type' => static::UINT, 'required' => true],
			'user_id' => ['type' => static::UINT, 'required' => true],
			'ip_address' => ['type' => static::BINARY, 'maxLength' => 16, 'required' => true],
			'log_date' => ['type' => static::UINT, 'default' => \XF::$time],
		];

		$structure->relations = [
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => self::TO_ONE,
				'conditions' => [['posid', '=', '$form_id']],
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