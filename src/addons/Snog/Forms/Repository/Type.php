<?php

namespace Snog\Forms\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Type extends Repository
{
	/**
	 * @return Finder
	 */
	public function findTypesForList()
	{
		return $this->finder('Snog\Forms:Type')->order('display', 'ASC');
	}

	public function deleteAllTypes()
	{
		$this->db()->emptyTable('xf_snog_forms_types');
	}

	public function createTypeTree($types, $rootId = 0)
	{
		return new \XF\Tree($types, 'display_parent', $rootId);
	}
}