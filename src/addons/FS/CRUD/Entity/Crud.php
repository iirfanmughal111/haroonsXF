<?php

namespace FS\CRUD\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Crud extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_crud';
        $structure->shortName = 'FS\CRUD:Crud';
        $structure->contentType = 'fs_crud';
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
