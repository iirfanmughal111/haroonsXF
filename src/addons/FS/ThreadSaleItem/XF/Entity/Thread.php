<?php

namespace FS\ThreadSaleItem\XF\Entity;
use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class Thread extends XFCP_Thread
{
    
    
    public static function getStructure(Structure $structure) {

        $structure = parent::getStructure($structure);

     
        $structure->columns['sale_item'] = ['type' => self::INT, 'default' => 0];
        
        

        return $structure;
    }
    
    
    
    
}