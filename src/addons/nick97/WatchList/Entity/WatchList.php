<?php

namespace nick97\WatchList\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class WatchList extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_watch_list_all';
        $structure->shortName = 'nick97\WatchList:WatchList';
        $structure->contentType = 'xf_watch_list_all';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],

            'user_id' => ['type' => self::UINT, 'required' => true],
            'thread_id' => ['type' => self::UINT, 'required' => true],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
