<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class PageCount extends Entity {

    public static function getStructure(Structure $structure) {
        
        $structure->table = 'bh_page_count';
        $structure->shortName = 'XenBulletins\BrandHub:PageCount';
        $structure->primaryKey = 'count_id';
        
        $structure->columns = [
            'count_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'page_id' => ['type' => self::UINT],
            'follow_count' => ['type' => self::UINT],
            'attachment_count' => ['type' => self::UINT],
 
        ];

        return $structure;
    }

}
