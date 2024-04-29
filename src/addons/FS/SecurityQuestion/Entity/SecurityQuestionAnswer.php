<?php

namespace FS\SecurityQuestion\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class SecurityQuestionAnswer extends Entity {

    public static function getStructure(Structure $structure) {
        $structure->table = 'fs_security_question_answer';
        $structure->shortName = 'FS\SecurityQuestion:SecurityQuestionAnswer';
        $structure->contentType = 'fs_security_question_answer';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'default' => null],
            'question_id' => ['type' => self::UINT, 'default' => null],
            'answer' => ['type' => self::STR, 'default' => null],
        ];

        $structure->relations = [
            'userQuestion' => [
                'entity' => 'FS\SecurityQuestion:SecurityQuestion',
                'type' => self::TO_ONE,
                'conditions' => 'question_id',
            ],
        ];

        return $structure;
    }

}
