<?php

namespace Snog\Movies\Repository;

class Company extends \XF\Mvc\Entity\Repository
{
	public function findCompaniesForList()
	{
		return $this->finder('Snog\Movies:Company')
			->setDefaultOrder('company_id', 'ASC');
	}
}