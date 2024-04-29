<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class PageSub extends Entity {

    public static function getStructure(Structure $structure) {
        
        $structure->table = 'bh_page_subscribe';
        $structure->shortName = 'XenBulletins\BrandHub:PageSub';
        $structure->primaryKey = 'sub_id';
        
        $structure->columns = [
            'sub_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT],
            'page_id' => ['type' => self::UINT],
 
        ];
        
       $structure->relations = [
      'User' => [
        'entity' => 'XF:User',
        'type' => self::TO_ONE,
        'conditions' => 'user_id'
      ],
              'Page' => [
                            'entity' => 'XenBulletins\BrandHub:OwnerPage',
                            'type' => self::TO_ONE,
                            'conditions' => [
                                    ['page_id', '=', '$page_id']
                            ],
                    ], 
           ];

        return $structure;
    }

}
