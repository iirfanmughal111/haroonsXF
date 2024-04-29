<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class ItemSub extends Entity {

    public static function getStructure(Structure $structure) {
        
        $structure->table = 'bh_item_subscribe';
        $structure->shortName = 'XenBulletins\BrandHub:ItemSub';
        $structure->primaryKey = 'sub_id';
        
        $structure->columns = [
            'sub_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT],
            'item_id' => ['type' => self::UINT],
            'create_date' => ['type' => self::UINT, 'default' => \XF::$time,],
 
        ];
        
       $structure->relations = [
      'User' => [
        'entity' => 'XF:User',
        'type' => self::TO_ONE,
        'conditions' => 'user_id'
      ],
              'Item' => [
                            'entity' => 'XenBulletins\BrandHub:Item',
                            'type' => self::TO_ONE,
                            'conditions' => [
                                    ['item_id', '=', '$item_id']
                            ],
                    ], 
           ];

        return $structure;
    }

}
