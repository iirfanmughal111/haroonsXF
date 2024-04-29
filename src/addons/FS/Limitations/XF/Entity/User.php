<?php

namespace FS\Limitations\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['daily_discussion_count']     = ['type' => self::UINT, 'max' => 65535, 'default' => 0];
        $structure->columns['conversation_message_count'] = ['type' => self::UINT, 'default' => 0];
        $structure->columns['daily_ads'] = ['type' => self::UINT, 'default' => 0];
        $structure->columns['daily_repost'] = ['type' => self::UINT, 'default' => 0];
        $structure->columns['last_repost'] = ['type' => self::UINT, 'default' => \XF::$time];
        // $structure->columns['media_storage_size']         = ['type' => self::UINT, 'default' => 0, 'max' => PHP_INT_MAX];


        return $structure;
    }
}
