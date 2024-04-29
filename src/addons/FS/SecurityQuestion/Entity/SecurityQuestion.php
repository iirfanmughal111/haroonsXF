<?php

namespace FS\SecurityQuestion\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class SecurityQuestion extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'fs_login_security_question';
        $structure->shortName = 'FS\SecurityQuestion:SecurityQuestion';
        $structure->contentType = 'fs_login_security_question';
        $structure->primaryKey = 'question_id';
        $structure->columns = [
            'question_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'security_question' => ['type' => self::STR, 'default' => null],
        ];

        return $structure;
    }

}
