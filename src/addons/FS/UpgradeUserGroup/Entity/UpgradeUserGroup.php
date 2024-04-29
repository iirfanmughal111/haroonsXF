<?php

namespace FS\UpgradeUserGroup\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class UpgradeUserGroup extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_upgrade_userGroup';
        $structure->shortName = 'FS\UpgradeUserGroup:UpgradeUserGroup';
        $structure->contentType = 'fs_upgrade_userGroup';
        $structure->primaryKey = 'usg_id';
        $structure->columns = [
            'usg_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'current_userGroup' => ['type' => self::UINT, 'default' => null],
            'message_count' => ['type' => self::UINT, 'default' => null],
            'last_login' => ['type' => self::UINT, 'default' => null],
            'upgrade_userGroup' => ['type' => self::UINT, 'default' => null],
        ];

        $structure->relations = [
            'UserGroup' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_group_id', '=', '$current_userGroup'],
                ],
            ],

            'UserGroups' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_group_id', '=', '$upgrade_userGroup'],
                ],
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
