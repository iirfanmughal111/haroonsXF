<?php

namespace Andy\Trader\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Trader extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_andy_trader';
		$structure->shortName = 'Andy\Trader:Trader';
		$structure->primaryKey = 'trader_id';
		$structure->columns = [
            'trader_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'timestamp' => ['type' => self::INT, 'default' => 0],
            'rating' => ['type' => self::INT, 'default' => 0],
            'seller_id' => ['type' => self::INT, 'default' => 0],
            'buyer_id' => ['type' => self::INT, 'default' => 0],
            'seller_comment' => ['type' => self::STR, 'default' => ''],
            'buyer_comment' => ['type' => self::STR, 'default' => '']
		];
		$structure->getters = [];
        $structure->relations = [
            'UserSeller' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'seller_id',
                'primary' => true
            ],
            'UserBuyer' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'buyer_id',
                'primary' => true
            ]
        ];
		return $structure;
	}	
}