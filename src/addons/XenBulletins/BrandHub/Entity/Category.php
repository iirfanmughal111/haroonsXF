<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class Category extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'bh_category';
        $structure->shortName = 'XenBulletins\BrandHub:Category';
        $structure->primaryKey = 'category_id';
        $structure->columns = [
            'category_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'category_title' => ['type' => self::STR, 'maxLength' => 100, 'required' => true]
        ];



        return $structure;
    }

}
