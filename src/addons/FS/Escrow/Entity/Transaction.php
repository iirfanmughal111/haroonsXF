<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Transaction extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow_transaction';
        $structure->shortName = 'FS\Escrow:Transaction';
        $structure->contentType = 'fs_escrow_transaction';
        $structure->primaryKey = 'transaction_id';
        $structure->columns = [
            'transaction_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'to_user' => ['type' => self::UINT, 'default' => 0],
            'escrow_id' => ['type' => self::UINT, 'default' => 0],
            'transaction_amount' => ['type' => self::FLOAT, 'required' => true],
            'current_amount' => ['type' => self::FLOAT, 'required' => true],
            'transaction_type' => ['type' => self::STR, 'default' => ''],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
            'Escrow' => [
                'entity' => 'FS\Escrow:Escrow',
                'type' => self::TO_ONE,
                'conditions' => 'escrow_id',
                'primary' => true
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
