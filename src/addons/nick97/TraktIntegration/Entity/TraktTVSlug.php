<?php

namespace nick97\TraktIntegration\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class TraktTVSlug extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_trakt_url_tv';
        $structure->shortName = 'nick97\TraktIntegration:TraktTVSlug';
        $structure->contentType = 'xf_trakt_url_tv';
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
