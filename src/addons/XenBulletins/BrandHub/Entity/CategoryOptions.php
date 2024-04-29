<?php


namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;
use XF\Util\Arr;

class CategoryOptions extends Entity {

    public static function getStructure(Structure $structure) {
        $options = \XF::options();

        $structure->table = 'bh_field_choice';
        $structure->shortName = ' XenBulletins\BrandHub:CategoryOptions';
        $structure->primaryKey = 'choice_id';
        $structure->columns = [
            'choice_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'category_id' => ['type' => self::UINT, 'default' => 0, 'api' => true],
            'field_id' => ['type' => self::STR, 'default' => 0, 'api' => true],
            'showdefault' => ['type' => self::UINT, 'default' => 1, 'api' => true],
            'field_choices' => ['type' => self::JSON_ARRAY, 'default' => []]
        ];

        return $structure;
    }

}
