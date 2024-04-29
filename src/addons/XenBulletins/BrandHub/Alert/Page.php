<?php

namespace XenBulletins\BrandHub\Alert;

use XF\Entity\UserAlert;
use XF\Mvc\Entity\Entity;
use XF\Alert\AbstractHandler;

class Page extends AbstractHandler
{
   
	public function canViewContent(Entity $entity, &$error = null)
	{
		return true;
	}

	public function getOptOutActions()
	{
		return ['data added'];
	}

	public function getOptOutDisplayOrder()
	{
		return 30000;
	}
}