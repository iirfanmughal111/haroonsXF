<?php

namespace XenBulletins\BrandHub\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;

class ItemRating extends Repository
{
	public function findReviewsInItem(\XenBulletins\BrandHub\Entity\Item $item, array $limits = [])
	{
		$finder = $this->finder('XenBulletins\BrandHub:ItemRating');
		$finder->where('item_id', $item->item_id)
			->where('is_review', 1);
                
                if(!$item->canViewDeletedContent())
                {
                    $finder->where('rating_state','visible');
                }
                
                $finder->setDefaultOrder('rating_date', 'desc');

		return $finder;
	}

	public function findLatestReviews()
	{
            
            $finder = $this->finder('XenBulletins\BrandHub:ItemRating');

            $finder->where([
                            'is_review' => 1
                    ]);
            if(!$item->canViewDeletedContent())
            {
                $finder->where('rating_state','visible');
            }
            $finder->setDefaultOrder('rating_date', 'desc');

//		$cutOffDate = \XF::$time - ($this->options()->readMarkingDataLifetime * 86400);
//		$finder->where('rating_date', '>', $cutOffDate);

		return $finder;
	}

}