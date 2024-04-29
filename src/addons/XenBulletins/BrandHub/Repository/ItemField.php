<?php

namespace XenBulletins\BrandHub\Repository;

use XF\Repository\AbstractField;

class ItemField extends AbstractField
{
	protected function getRegistryKey()
	{
		return 'bhItemfield';
	}

	protected function getClassIdentifier()
	{
		return 'XenBulletins\BrandHub:ItemField';
	}

	public function getDisplayGroups()
	{
		return [
                    'above_record' => \XF::phrase('bh_main_container_item'),
			'below_record' => \XF::phrase('bh_side_bar_item'),
			
		];
	}
        
        
        
}