<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    protected function _postSave()
    {

            $visibilityChange = $this->isStateChanged('discussion_state', 'visible');
            
             $itemId = $this->item_id;
            if ($this->isUpdate() && $itemId)
            {
         
                    if ($visibilityChange == 'enter')
                    {                  
                            \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'plus');
                            \XenBulletins\BrandHub\Helper::updateOwnerPageDiscussionCount($itemId, $this->user_id,'plus');
                    }
                    else if ($visibilityChange == 'leave')
                    {                 
                            \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'minus');
                            \XenBulletins\BrandHub\Helper::updateOwnerPageDiscussionCount($itemId, $this->user_id,'minus');
                    }              
            }
            
            return parent::_postSave();
    }
                
    
    protected function _postDelete()
    {
        $itemId = $this->item_id;
        if($itemId && ($this->discussion_state != 'deleted'))
        {                   
            \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'minus');
            \XenBulletins\BrandHub\Helper::updateOwnerPageDiscussionCount($itemId, $this->user_id,'minus');
        }
        
        return parent::_postDelete();
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->columns['item_id'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
    
    
}