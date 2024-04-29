<?php

namespace Snog\Forms\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $appid
 * @property string $type
 * @property bool $active
 * @property bool $sidebar
 * @property bool $navtab
 * @property array $user_criteria
 * @property int $display_parent
 * @property int $display
 *
 * RELATIONS
 * @property Form $Form
 *
 * @property Type $Type
 */
class Type extends Entity implements ExportableInterface
{
	public function checkUserCriteriaMatch(\XF\Entity\User $user, $matchOnEmpty = false)
	{
		if ($this->user_criteria)
		{
			$userCriteria = $this->app()->criteria('XF:User', $this->user_criteria);
			$userCriteria->setMatchOnEmpty($matchOnEmpty);
			return $userCriteria->isMatched($user);
		}

		return true;
	}

	protected function verifyUserCriteria(&$criteria)
	{
		$userCriteria = $this->app()->criteria('XF:User', $criteria);
		$criteria = $userCriteria->getCriteria();
		return true;
	}


	public function getExportData(): array
	{
		return [
			'appid' => $this->appid,
			'type' => htmlspecialchars($this->type),
			'active' => ($this->active) ? 1 : 0,
			'sidebar' => ($this->sidebar) ? 1 : 0,
			'navtab' => ($this->navtab) ? 1 : 0,
			'user_criteria' => serialize($this->user_criteria),
			'display_parent' => $this->display_parent,
			'display' => $this->display
		];
	}

	/************************* LIFE-CYCLE ***************************/

	protected function _postDelete()
	{
		parent::_postDelete();

		/** @var \XF\Repository\Option $optionRepo */
		$optionRepo = $this->repository('XF:Option');
		$optionRepo->updateOption('snogFormsLastUpdate', \XF::$time);
	}

	protected function _preSave()
	{
		if (!$this->user_criteria)
		{
			$this->error(\XF::phrase('snog_forms_type_criteria_error'));
		}

		if ($this->hasChanges())
		{
			/** @var \XF\Repository\Option $optionRepo */
			$optionRepo = $this->repository('XF:Option');
			$optionRepo->updateOption('snogFormsLastUpdate', \XF::$time);
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_snog_forms_types';
		$structure->shortName = 'Snog\Forms:Type';
		$structure->contentType = 'type';
		$structure->primaryKey = 'appid';
		$structure->columns = [
			'appid' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'type' => ['type' => self::STR, 'maxLength' => 100, 'required' => 'snog_forms_type_error'],
			'active' => ['type' => self::BOOL, 'default' => false],
			'sidebar' => ['type' => self::BOOL, 'default' => false],
			'navtab' => ['type' => self::BOOL, 'default' => false],
			'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
			'display_parent' => ['type' => self::UINT, 'default' => 0],
			'display' => ['type' => self::UINT, 'default' => 0],
		];

		$structure->getters = [];
		$structure->relations = [
			'Form' => [
				'entity' => 'Snog\Forms:Form',
				'type' => self::TO_ONE,
				'conditions' => 'appid',
				'key' => 'appid'
			]
		];

		return $structure;
	}
}
