<?php

namespace XenBulletins\BrandHub\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread {

    public function actionEdit(ParameterBag $params) { 
        $visitor = \xf::visitor();
        $parent = parent::actionEdit($params);
        if(!$visitor->hasPermission('bh_brand_hub', 'bh_can_assignThreadsToHub'))
        {
            return $parent;
        }
        
        if ($parent instanceof \XF\Mvc\Reply\View && !$this->isPost()) 
        {            
            $forum = $parent->getParam('forum');

            if($forum && $forum->brand_id)
            {
                $items = $this->Finder('XenBulletins\BrandHub:Item')->where('brand_id', $forum->brand_id)->fetch();
                 $parent->setParam('items', $items);
            }     

        }

        return $parent;
    }
    
    

    protected function setupThreadEdit(\XF\Entity\Thread $thread) {
        
        $alreadyItemId = $thread->item_id;
        
        $itemId = $this->filter('item_id' , 'UINT');    
        
        $thread->item_id = $itemId;
//        $thread->save();
        
        if($itemId!=$alreadyItemId){
            
       $itemUpdate = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $itemId)->fetchOne();

            if($itemUpdate)
            {

                  $requests = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $alreadyItemId)->with(['User', 'Item'])->fetch();

                   $detail="new thread ".$thread->title;

                    foreach ($requests as $request) {

                       $link = $this->app->router('public')->buildLink('threads', $thread);

                       \XenBulletins\BrandHub\Helper::updateItemNotificiation($itemUpdate->item_title, $link, $detail, $request->User);
                   }
            }

      }
         
      if($itemId != $alreadyItemId)
      {
            if(!$alreadyItemId && $itemId)
            {
                \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'plus');
            }

            else if($alreadyItemId && !$itemId)
            {
                \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($alreadyItemId,'minus');
            }

            else if ($itemId && $alreadyItemId)
            {
                \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($alreadyItemId,'minus');               
                \XenBulletins\BrandHub\Helper::updateItemDiscussionCount($itemId,'plus');
            }
      }
       
        return parent::setupThreadEdit($thread);
    }
    
    

}
