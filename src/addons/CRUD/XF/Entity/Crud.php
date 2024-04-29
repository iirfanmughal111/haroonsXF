<?php

namespace CRUD\XF\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Crud extends Entity
{

    public static function getStructure(Structure $structure): Structure
    {
        $structure->table = 'xf_crud';
        $structure->shortName = 'CRUD\XF:Crud';
        $structure->contentType = 'xf_crud';
        $structure->primaryKey = 'id';

        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],
            'name' => ['type' => self::STR, 'maxLength' => 20, 'required' => true],
            'class' =>  ['type' => self::STR, 'required' => true],
            'rollNo' => ['type' => self::UINT, 'required' => true],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
