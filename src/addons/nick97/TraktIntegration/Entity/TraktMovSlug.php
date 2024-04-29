<?php

namespace nick97\TraktIntegration\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class TraktMovSlug extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_trakt_url_movies';
        $structure->shortName = 'nick97\TraktIntegration:TraktMovSlug';
        $structure->contentType = 'xf_trakt_url_movies';
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
