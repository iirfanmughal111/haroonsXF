<?php

namespace XenBulletins\BrandHub\Repository;

use XF\Repository\AbstractFieldMap;

class CategoryField extends AbstractFieldMap
{
	protected function getMapEntityIdentifier()
	{
		return 'XenBulletins\BrandHub:CategoryField';
	}

	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('CategoryFields');
	}

	protected function updateAssociationCache(array $cache)
	{
		$categoryIds = array_keys($cache);
		$categories = $this->em->findByIds('XenBulletins\BrandHub:Category', $categoryIds);

		foreach ($categories AS $category)
		{
			$category->field_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}