<?php


namespace XenBulletins\BrandHub\Entity;

use XF\Entity\AbstractFieldMap;
use XF\Mvc\Entity\Structure;


class CategoryField extends AbstractFieldMap
{
	public static function getContainerKey()
	{
		return 'category_id';
	}

	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure($structure, 'bh_category_field', ' XenBulletins\BrandHub:CategoryField', ' XenBulletins\BrandHub:ItemField');
                

		$structure->relations['Item'] = [
			'entity' => 'XenBulletins\BrandHub:Item',
			'type' => self::TO_ONE,
			'conditions' => 'category_id',
			'primary' => true
		];

		return $structure;
	}
}