<?php

namespace XenBulletins\BrandHub\InlineMod;


use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
	public function getPossibleActions()
	{
            $actions = parent::getPossibleActions();
            
             $visitor = \XF::visitor();
            if($visitor->hasPermission('bh_brand_hub', 'bh_can_assignThreadsToHub'))
            {
                $actions['link_to_brand_hub'] = $this->getActionHandler('XenBulletins\BrandHub:Thread\AssignItem');
            }
            return $actions;
	}

}