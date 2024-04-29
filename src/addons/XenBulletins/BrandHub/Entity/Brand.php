<?php

namespace XenBulletins\BrandHub\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class Brand extends Entity {
    
     public function getBreadcrumbs($includeSelf = true)
	{

		if ($includeSelf)
		{
			$breadcrumbs[] = [
				'href' => $this->app()->router()->buildLink('bh_brands/brand', $this),
				'value' => $this->brand_title
			];
		}

		return $breadcrumbs;
	}


    public static function getStructure(Structure $structure) {
        $structure->table = 'bh_brand';
        $structure->shortName = 'XenBulletins\BrandHub:Brand';
        $structure->primaryKey = 'brand_id';
        $structure->columns = [
            'brand_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'brand_title' => ['type' => self::STR, 'maxLength' => 100, 'required' => true],
            'discussion_count' => ['type' => self::UINT, 'default' => 0],
            'view_count' => ['type' => self::UINT, 'default' => 0],
            'rating_count' => ['type' => self::UINT, 'default' => 0],
            'rating_sum' => ['type' => self::UINT, 'default' => 0],
            'rating_avg' => ['type' => self::FLOAT, 'default' => 0],
            'rating_weighted' => ['type' => self::UINT, 'default' => 0],
            'review_count' => ['type' => self::UINT, 'default' => 0],
            'node_ids' => ['type' => self::JSON_ARRAY, 'default' => []],
            'forums_link' => ['type' => self::STR, 'maxLength' => 100, 'default' => '' ],
            'website_link' => ['type' => self::STR, 'maxLength' => 100, 'default' => '' ],
            'for_sale_link' => ['type' => self::STR, 'maxLength' => 100, 'default' => '' ],
            'intro_link' => ['type' => self::STR, 'maxLength' => 100, 'default' => '' ],
            'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            
            
            'Description' => [
                            'entity' => 'XenBulletins\BrandHub:BrandDescription',
                            'type' => self::TO_ONE,
                            'conditions' => [['brand_id', '=', '$brand_id']],
                            'primary' => true
                    ],
        ];
        
        



        return $structure;
    }

}
