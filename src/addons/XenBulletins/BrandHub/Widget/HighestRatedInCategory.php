<?php

namespace XenBulletins\BrandHub\Widget;

class HighestRatedInCategory extends \XF\Widget\AbstractWidget {

    public function render() {


        $explodeUrl = explode("/", $_SERVER['REQUEST_URI']);

      
        $itemId = substr($explodeUrl[3], -1);

        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $itemId)->fetchOne();
        
        if ($item) {

            $numberOfItems = \XF::options()->bh_highest_rated_items;
            $highestRatedItems = $this->Finder('XenBulletins\BrandHub:Item')->where('category_id', $item->Category->category_id)->order('rating_avg', 'DESC')->fetch($numberOfItems);
        }
        
        $viewParams = [
            'highestRatedItems' => $highestRatedItems,
        ];
        return $this->renderer('bh_highestRatedItems', $viewParams);
    }

}