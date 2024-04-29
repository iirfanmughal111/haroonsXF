<?php

namespace Snog\TV\Repository;

class Company extends \XF\Mvc\Entity\Repository
{
	public function findCompaniesForList()
	{
		return $this->finder('Snog\TV:Company')
			->setDefaultOrder('company_id', 'ASC');
	}
}