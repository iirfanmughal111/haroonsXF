<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class BrandDescription extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'bh_brand_description';
        $structure->shortName = 'XenBulletins\BrandHub:BrandDescription';
        $structure->primaryKey = 'desc_id';
        $structure->columns = [
            'desc_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'brand_id' => ['type' => self::UINT],
            'description' => ['type' => self::STR]
        ];



        return $structure;
    }

}
