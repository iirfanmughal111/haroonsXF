<?php

namespace FS\BunnyIntegration\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class DeleteBunnyVid extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_bunny_thread_videos';
        $structure->shortName = 'FS\BunnyIntegration:DeleteBunnyVid';
        $structure->contentType = 'fs_bunny_thread_videos';
        $structure->primaryKey = 'req_id';
        $structure->columns = [
            'req_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'thread_id' => ['type' => self::UINT, 'default' => 0],
            'bunny_library_id' => ['type' => self::UINT, 'default' => 0],
            'bunny_video_id' => ['type' => self::STR, 'default' => null],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
