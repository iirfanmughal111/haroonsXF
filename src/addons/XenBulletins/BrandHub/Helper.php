<?php

namespace XenBulletins\BrandHub;

class Helper {

    public static function updateItemDiscussionCount($itemId, $count = 'plus') {
        
        $item = \XF::finder('XenBulletins\BrandHub:Item')->where('item_id', $itemId)->fetchOne();
//        var_dump($itemId);exit;
        if ($item) {
            $brand = $item->Brand;

            if ($count == 'plus') {
                $item->discussion_count += 1;
                $brand->discussion_count += 1;
            }
            if ($count == 'minus') {
                $item->discussion_count -= 1;
                $brand->discussion_count -= 1;
            }

            $item->save();
            $brand->save();
        }
    }
    
    public static function updateRatingAndReviewCount(\XenBulletins\BrandHub\Entity\ItemRating $rating, $count = 'plus') {
        $item = $rating->Item;
        if ($item) {
            $brand = $item->Brand;

            if ($count == 'plus') {
                
                $item->rating_count += 1;
                $brand->rating_count += 1;
                
                if($rating->is_review)
                {
                    $item->review_count += 1;
                    $brand->review_count += 1;
                }
            }
            if ($count == 'minus') {
                
                $item->rating_count -= 1;
                $brand->rating_count -= 1;
                
                if($rating->is_review)
                {
                    $item->review_count -= 1;
                    $brand->review_count -= 1;
                }
            }
            
            
            $self = new self;
            $itemRatingSum = $self->getItemRatingSum($item->item_id);
            $item->rating_sum = $itemRatingSum["sum"] ? $itemRatingSum["sum"] : 0;
            
            if($item->rating_count)
            {
                $item->rating_avg = round($itemRatingSum["sum"] / $item->rating_count, 1);
            }
            else
            {
                $item->rating_avg = 0;
            }
            
            $item->save();
            
            $brandRatingSum = $self->getBrandRatingSum($brand->brand_id);

            $brand->rating_sum = $brandRatingSum["sum"];
            
            if($brand->rating_count)
            {
                $brand->rating_avg = round($brandRatingSum["sum"] / $brand->rating_count, 1);
            }
            else
            {
                $brand->rating_avg = 0;
            }
            
            $brand->save();
        }
    }
    
    protected function getItemRatingSum($itemId)
    {
        $itemRatingSum = \xf::db()->fetchRow("
			SELECT SUM(rating) AS sum
			FROM bh_item_rating
			WHERE item_id = ?
				AND rating_state = 'visible'
		", $itemId);
        
        return $itemRatingSum;
    }
    
    protected function getBrandRatingSum($brandId)
    {
        $brandRatingSum = \xf::db()->fetchRow("
			SELECT SUM(rating_sum) AS sum
			FROM bh_item
			WHERE brand_id = ?
				AND item_state = 'visible'
		", $brandId);
        
        return $brandRatingSum;
    }
    
  

    public static function updateItemNotificiation($title, $link, $detail, $reciver) {


//        $visitor = \XF::visitor();
        $status = "display";

        $alertRepo = \XF::app()->repository('XF:UserAlert');


        $alerted = $alertRepo->alert(
                $reciver,
                1,
                "admin",
                'bh_item',
                1,
                $status,
                [
                    'title' => $title,
                    'detail' => $detail,
                    'link' => $link
                ],
                ['autoRead' => true]
        );
    }

    public static function updatePageNotificiation($title, $link, $detail, $reciver) {


//        $visitor = \XF::visitor();
        $status = "display";

        $alertRepo = \XF::app()->repository('XF:UserAlert');

        $alerted = $alertRepo->alert(
                $reciver,
                 1,
                "admin",
                'bh_ownerpage',
                1,
                $status,
                [
                    'title' => $title,
                    'detail' => $detail,
                    'link' => $link
                ],
                ['autoRead' => true]
        );
    }
    
    
    public static function updateOwnerPageDiscussionCount($itemId, $userId, $count = 'plus') {
        
        $ownerPage = \XF::finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $itemId)->where('user_id', $userId)->fetchOne();
        if($ownerPage)
        {
            if ($count == 'plus') {
                 $ownerPage->discussion_count += 1;
            }
            if ($count == 'minus') {
                 $ownerPage->discussion_count -= 1;
            }
           
            $ownerPage->save();
        }
    
    }

}