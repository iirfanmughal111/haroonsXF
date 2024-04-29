<?php

/** 
  * @author Thomas Braunberger
  */

namespace Awedo\PostAreas\Entity;

use XF\Mvc\Entity\Structure;

class PostAreas extends \XF\Mvc\Entity\Entity
{
    public static function getStructure (Structure $structure)
    {
        $structure->table = 'xf_awedo_post_areas';
        $structure->shortName = 'Awedo\PostAreas:PostAreas';
        $structure->primaryKey = ['user_id', 'node_id'];
        $structure->columns = [
            'user_id' => ['type' => self::UINT ],
            'node_id' => ['type' => self::UINT ],
            'post_count' => ['type' => self::UINT, 'default' => 0 ],
            'thread_count' => ['type' => self::UINT, 'default' => 0 ]
        ];

        return $structure;
    }    
}