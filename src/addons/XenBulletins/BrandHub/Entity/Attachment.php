<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;

class Attachment extends XFCP_Attachment {

    public function canView(&$error = null) {
        
   if ($this->content_type == 'bh_item' || $this->content_type == 'bh_ownerpage' ) {
            return TRUE;
        }
        return parent::canView($error);
    }
    
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->columns['page_id'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['user_id'] =  ['type' => self::UINT, 'default' => 0];
         $structure->columns['item_main_photo'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['page_main_photo'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
}