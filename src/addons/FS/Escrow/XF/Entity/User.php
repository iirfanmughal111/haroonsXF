<?php

namespace FS\Escrow\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['deposit_amount'] =  ['type' => self::FLOAT, 'required' => true];

        return $structure;
    }
}
