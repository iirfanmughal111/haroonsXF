<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Forum extends XFCP_Forum
{
    


    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->columns['brand_id'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }

    
    
    
    
    
}