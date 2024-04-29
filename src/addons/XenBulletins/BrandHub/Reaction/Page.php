<?php

namespace XenBulletins\BrandHub\Reaction;

use XF\Mvc\Entity\Entity;
use XF\Reaction\AbstractHandler;

class Page extends AbstractHandler {

    public function reactionsCounted(Entity $entity) {


        if ($entity->page_state == 'visible') {

            return true;
        }
    }
    
    	public function canViewContent(Entity $entity, &$error = null)
	{
	
            return true;
	}



}

?>