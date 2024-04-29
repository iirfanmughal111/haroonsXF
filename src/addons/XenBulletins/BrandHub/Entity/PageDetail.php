<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class PageDetail extends Entity {

    public static function getStructure(Structure $structure) {
        
        $structure->table = 'bh_owner_page_detail';
        $structure->shortName = 'XenBulletins\BrandHub:PageDetail';
        $structure->primaryKey = 'detail_id';
        
        $structure->columns = [
            'detail_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'page_id' => ['type' => self::UINT],
            'about' => ['type' => self::STR],
            'attachment' => ['type' => self::STR],
            'customizations' => ['type' => self::STR],
        ];

        return $structure;
    }

}
