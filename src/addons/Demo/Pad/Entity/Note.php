<?php

namespace Demo\Pad\Entity;

use XF\BbCode\ProcessorAction\Censor;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Note extends Entity
{
    // protected function _preSave()
    // {
    //     if ($this->isUpdate())
    //     {
    //         $this->edit_date = \XF::$time;
    //     }
    // }

    // protected function verifyTitle(&$value)
    // {
    //     if (strlen($value) < 10) {
    //         $this->error('Please enter a proper title', 'title');
    //         return false;
    //     }

    //     // first letter ko upper case me convert krney k liye use hota hai
    //     $value = utf8_ucwords($value);

    //     return true;
    // }

    // public function getFirstWord()
    // {
    //     return explode($this->content, ' ')[0];
    // }

    public static function getStructure(Structure $structure): Structure
    {
        $structure->table = 'demo_pad_note';
        $structure->shortName = 'Demo\Pad:Note';
        $structure->contentType = 'demo_pad_note';
        $structure->primaryKey = 'note_id';
        $structure->columns = [
            'note_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'default' => 0],
            'title' =>  ['type' => self::STR, 'maxLength' => 255, 'required' => true],
            'content' => ['type' => self::STR, 'required' => true],
            'post_date' => ['type' => self::UINT, 'default' => 0],
            'edit_date' => ['type' => self::UINT, 'default' => 0],
        ];

        $structure->relations = [
            // 'User' => [
            //     'entity' => 'XF:User',
            //     'type' => self::TO_ONE,
            //     'conditions' => 'user_id',
            //     'primary' => true
            // ]
        ];
        $structure->defaultWith = [];
        // $structure->defaultWith = ['User'];
        $structure->getters = [
            // 'firstWord' => true
        ];
        $structure->behaviors = [];

        return $structure;
    }
}