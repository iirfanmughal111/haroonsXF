<?php

namespace nick97\TraktMovies\Repository;

class Company extends \XF\Mvc\Entity\Repository
{
	public function findCompaniesForList()
	{
		return $this->finder('nick97\TraktMovies:Company')
			->setDefaultOrder('company_id', 'ASC');
	}
}