<?php

namespace FS\AutoForumManager\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class AutoForumManager extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_auto_forum_manage';
        $structure->shortName = 'FS\AutoForumManager:AutoForumManager';
        $structure->contentType = 'fs_auto_forum_manage';
        $structure->primaryKey = 'forum_manage_id';
        $structure->columns = [
            'forum_manage_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'admin_id' => ['type' => self::UINT, 'default' => null],
            'node_id' => ['type' => self::UINT, 'default' => null],
            'last_login_days' => ['type' => self::UINT, 'default' => null],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$admin_id'],
                ],
            ],

            'Node' => [
                'entity' => 'XF:Node',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
