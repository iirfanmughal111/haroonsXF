<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class ItemDescription extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'bh_item_description';
        $structure->shortName = 'XenBulletins\BrandHub:ItemDescription';
        $structure->primaryKey = 'desc_id';
        $structure->columns = [
            'desc_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'item_id' => ['type' => self::UINT],
            'description' => ['type' => self::STR]
        ];



        return $structure;
    }

}
