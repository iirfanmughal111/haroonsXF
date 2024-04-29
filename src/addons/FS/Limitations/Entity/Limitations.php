<?php

namespace FS\Limitations\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Limitations extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_limitations_user_groups';
        $structure->shortName = 'FS\Limitations:Limitations';
        $structure->contentType = 'fs_limitations_user_groups';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],

            'user_group_id' => ['type' => self::UINT, 'required' => true],
            'node_ids' => ['type' => self::STR, 'required' => true],
            'daily_ads' => ['type' => self::UINT, 'default' => 0],
            'daily_repost' => ['type' => self::UINT, 'default' => 0],
        ];

        $structure->relations = [
            'UserGroup' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => 'user_group_id',
            ]
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
