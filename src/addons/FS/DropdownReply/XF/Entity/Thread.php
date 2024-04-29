<?php

namespace FS\DropdownReply\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['dropdwon_options'] =  ['type' => self::JSON_ARRAY, 'nullable' => true, 'default' => null];
        $structure->columns['is_dropdown_active'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
    

}