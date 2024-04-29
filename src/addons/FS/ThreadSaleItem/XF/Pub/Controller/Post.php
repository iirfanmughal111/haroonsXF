<?php

namespace FS\ThreadSaleItem\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XC\ThreadAds\Helper;

class Post extends XFCP_Post {

    public function actionEdit(ParameterBag $params) {

        $parent = parent::actionEdit($params);

        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);
        
         $saleItemService = $this->service('FS\ThreadSaleItem:SaleItem');

        if(!$this->isPost()){
            
               
                
                $forum=$post->Thread->Forum;

                
                if ($parent instanceof \XF\Mvc\Reply\View && $post->isFirstPost() &&
                               
                               \XF::visitor()->hasPermission('fs_threadsaleitem', 'fs_saleitem'))
                                 {
                    
                    
                                    $parent->setParam('sale_item', $saleItemService->allowsaleItem(null,$forum->node_id));

                                    return $parent;


                 }
                
                
                return $parent;
        }
         
        
        $forum=$post->Thread->Forum;
        
        if ($this->isPost() && $post->isFirstPost() && $saleItemService->allowsaleItem(null,$forum->node_id)) {
             
                            $thread = $post->Thread;
                            $saleItem = $this->filter('sale_item', 'int');
                            $thread->sale_item = $saleItem;
                            $thread->save();
                            
               
         }
        
        return $parent;
    }

}
