<?php

namespace nick97\TraktTV\Repository;

class Company extends \XF\Mvc\Entity\Repository
{
	public function findCompaniesForList()
	{
		return $this->finder('nick97\TraktTV:Company')
			->setDefaultOrder('company_id', 'ASC');
	}
}
