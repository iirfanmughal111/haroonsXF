<?php

namespace XC\ThreadGPTBot\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class BotOption extends Entity {


    public static function getStructure(Structure $structure) {
        $structure->table = 'thread_bot_options';
        $structure->shortName = 'XC\ThreadGPTBot:BotOption';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],
            'title' => ['type' => self::STR, 'default' => ''],
            'bot_instruction' => ['type' => self::STR, 'default' => ''],
            
        ];

        return $structure;
    }

}
