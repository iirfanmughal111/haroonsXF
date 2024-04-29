<?php

namespace FS\MediaTagSetting\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class MediaTag extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'fs_media_tag';
        $structure->shortName = 'FS\MediaTagSetting:MediaTag';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],
            'title' => ['type' => self::STR, 'required' => true],
            'media_site_ids' => ['type' => self::LIST_COMMA, 'required' => true],
            'user_group_ids' => ['type' => self::LIST_COMMA, 'required' => true],
            'custom_message' => ['type' => self::STR, 'required' => true],
            'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
        ];


        return $structure;
    }

}
