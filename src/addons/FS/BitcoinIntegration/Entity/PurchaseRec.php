<?php

namespace FS\BitcoinIntegration\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class PurchaseRec extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_bitcoin_purchase_record';
        $structure->shortName = 'FS\BitcoinIntegration:PurchaseRec';
        $structure->contentType = 'fs_bitcoin';
        $structure->primaryKey = 'id';
        $structure->columns = [
            'id' => ['type' => self::UINT, 'autoIncrement' => true],

            'user_id' => ['type' => self::UINT, 'required' => true],
            'user_upgrade_id' => ['type' => self::UINT, 'required' => true],
            'status' => ['type' => self::UINT, 'default' => 0],
            'end_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
