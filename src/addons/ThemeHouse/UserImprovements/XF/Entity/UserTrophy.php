<?php

namespace ThemeHouse\UserImprovements\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserTrophy extends XFCP_UserTrophy
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns = array_merge($structure->columns, [
            'th_showcased' => ['type' => self::BOOL, 'default' => 0]
        ]);

        return $structure;
    }
}
