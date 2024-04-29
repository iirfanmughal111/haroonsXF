<?php

namespace nick97\TraktTV\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class TraktTVSlug extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'nick97_trakt_tv_url';
        $structure->shortName = 'nick97\TraktTV:TraktTVSlug';
        $structure->contentType = 'nick97_trakt_tv_url';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],

            'tmdb_id' => ['type' => self::UINT, 'required' => true],
            'trakt_slug' => ['type' => self::STR, 'default' => null],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
