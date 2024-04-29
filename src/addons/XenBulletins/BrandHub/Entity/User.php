<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    
            public function canViewItemsOnProfile(&$error = null)
	{
            $visitor = \xf::visitor();
            
               if($visitor->hasPermission('bh_brand_hub', 'can_view_subscribe_items') || $visitor->user_id == $this->user_id)
               {
                   return true;
               }
               
		return false;
	}
        
        public function canViewOwnerPagesOnProfile(&$error = null)
	{
            
            $visitor = \xf::visitor();
            
               if($visitor->hasPermission('bh_brand_hub', 'bh_canViewOwnerPageTab') || $visitor->user_id == $this->user_id)
               {
                   return true;
               }
               
		return false;
	}
    
}