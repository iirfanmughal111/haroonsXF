<?php

namespace XenBulletins\BrandHub\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum {


    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator) {
        $createThreds = parent::finalizeThreadCreate($creator);

        $itemId = $this->filter('item_id', 'uint');
        
        $thread = $creator->getThread();
        $thread->item_id = $itemId;
        
        $thread->save();
        
        if($itemId){
           
               $requests = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $itemId)->with(['User', 'Item'])->fetch();
               
                $detail="new thread".$thread->title;

                 foreach ($requests as $request) {

                    $link = $this->app->router('public')->buildLink('threads', $thread);
                  
                    \XenBulletins\BrandHub\Helper::updateItemNotificiation($request->Item->item_title, $link, $detail, $request->User);
                }
            
        }
        
        \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId); 
        \XenBulletins\BrandHub\Helper::updateOwnerPageDiscussionCount($itemId, $thread->user_id,'plus');

        return $createThreds;
    }

    public function actionPostThread(ParameterBag $params) {
 
        $postThread = parent::actionPostThread($params);
        if ($postThread instanceof \XF\Mvc\Reply\View) 
        {
            
            $forum = $this->finder('XF:Forum')->where('node_id',$params->node_id)->fetchOne();
            if($forum && $forum->brand_id)
            {
                $items = $this->Finder('XenBulletins\BrandHub:Item')->where('brand_id', $forum->brand_id)->fetch();
                $postThread->setParam('items', $items);
            }     
            
        }
        
        return $postThread;
    }
    
     public function actionForum(ParameterBag $params) {

        $forum = parent::actionForum($params);

        $brand_id = $forum->getParam('forum')->brand_id;

        $hideitemsBlock = \XF::options()->hide_forum_items;

        if ($forum instanceof \XF\Mvc\Reply\View && $brand_id && !$hideitemsBlock) {

            $limit_items = \XF::options()->forum_items_display;

            $brand = $this->finder('XenBulletins\BrandHub:Brand')->where('brand_id', $brand_id)->fetchOne();


             $items = $this->finder('XenBulletins\BrandHub:Item')->where('brand_id', $brand_id)->fetch();
 
             $itemCount=count($items);
       
            $items = $this->finder('XenBulletins\BrandHub:Item')->where('brand_id', $brand_id)->order('discussion_count','DESC')->fetch($limit_items);
            
            if (count($items) > 0) {

                $forum->setParam('items', $items);
                $forum->setParam('brand', $brand);
                $forum->setParam('itemCount', $itemCount);
            }
        }
        
        return $forum;
     }
    

}
