<?php

namespace XenBulletins\BrandHub\Bookmark;
use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{

    
//    	public function getEntityWith()
//	{
//	
//
//		return ['Item'];
//	}
//    
    public function getContentUser(Entity $content)
	{
        
       
 
        if($content->User){
            
            return $content->User;
        }
    
			
		
	}
        
        
        public function canViewContent(Entity $content, &$error = null)
	{
            return true;
		
	}
        
        
        
    
}



?>