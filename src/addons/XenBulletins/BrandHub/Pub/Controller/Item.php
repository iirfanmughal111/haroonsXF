<?php

namespace XenBulletins\BrandHub\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XenBulletins\BrandHub\Entity;
use XF\Mvc\ParameterBag;


class Item extends AbstractController
{
     
    protected function preDispatchController($action, ParameterBag $params) 
    {
        if ($action == 'Itemsub' || $action == 'Rate')
        {
            $this->assertLoggedIn();
        }
        
    }
    
   
    
        public function actionIndex(ParameterBag $params) {
        
        
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->with(['Description','Category','Attachment'])->fetchOne();
        $itemPages = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $params->item_id)->fetch(10);
  
        $item->view_count += 1;
        $item->save();
        
        $brand = $item->Brand;
        $brand->view_count += 1;
        $brand->save();
 
        

        $attachmentData = $this->finder('XF:Attachment')->where('content_id', $item->item_id)->where('content_type', 'bh_item')->where('item_main_photo', 1)->order('attach_date','Desc')->fetchOne();
 
        if(!$attachmentData){
            
          $attachmentData = $this->finder('XF:Attachment')->where('content_id', $item->item_id)->where('content_type', 'bh_item')->order('attach_date','Desc')->fetchOne();
  
        }
        
        $attachment_id = $this->filter('attachment_id', 'STR');
        

        $attachmentItem = "";
        
        $filmStripPluginlist = "";

         $filmStripPlugin = $this->plugin('XenBulletins\BrandHub:FilmStrip');

         if ($attachment_id) {

            $attachmentItem = $this->assertViewableAttachmentItem($attachment_id);
            $filmStripPluginlist = $filmStripPlugin->getFilmStripParamsForView($attachmentItem);
            
        } elseif ( $attachmentData) {

            $filmStripPluginlist = $filmStripPlugin->getFilmStripParamsForView($attachmentData);
        }
        
        
        $discussions = $this->finder('XF:Thread')->where('item_id', $params->item_id)->where('discussion_state','visible')->order('thread_id','DESC')->fetch(\xf::options()->bh_discussions_on_item);
        
        $alreadySub = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id',$params->item_id)->where('user_id',\XF::visitor()->user_id)->fetchOne();
        
        
        
  
        $itemReviews = $this->em()->getEmptyCollection();
	
        $recentReviewsMax = $this->options()->bh_RecentReviewsCount;
        if ($recentReviewsMax)
        {
                $ratingRepo = $this->repository('XenBulletins\BrandHub:ItemRating');
                $itemReviews = $ratingRepo->findReviewsInItem($item)->fetch($recentReviewsMax);

        }
		
        
//        $itemRatings = $this->finder('XenBulletins\BrandHub:ItemRating')->where('item_id', $params->item_id)->fetch()->groupBy('rating');
//         krsort($itemRatings);
         

         
        $itemRatings = [];
        for($i=5; $i > 0; $i--)
        {
             $itemRating = $this->finder('XenBulletins\BrandHub:ItemRating')->where('item_id', $params->item_id)->where('rating_state','visible')->where('rating',$i)->fetch();
             if($item->rating_count)
             {
                $itemRatings[$i] = round((count($itemRating)/$item->rating_count) * 100);
             }
             else
             {
                 $itemRatings[$i] = 0;
             }
        }
        

                
                $itemPosition = $this->getRankRecord($item,$item->Category->category_id);
       
     
        $viewParams = [
            'filmStripParams' => $filmStripPluginlist,
            'mainItem' => $attachment_id?$attachmentItem:$attachmentData,
            'item' => $item,
            'itemReviews' => $itemReviews,
            'itemRatings' => $itemRatings,
            'discussions' => $discussions,
             'itemPages'=>$itemPages,
            'ownerPageTotal' => count($itemPages),
            'alreadySub'=>$alreadySub,
            'itemPosition' => $itemPosition,
        ];
        return $this->view('XenBulletins\BrandHub:Brand', 'bh_item_detail', $viewParams);
    }
    
        protected function assertViewableAttachmentItem($attachmentId)
	{
    
	
		$attachmentitem = $this->em()->find('XF:Attachment', $attachmentId);

		if (!$attachmentitem)
		{
			throw $this->exception($this->notFound(\XF::phrase('bh_requested_media_item_not_found')));
		}


		return $attachmentitem;
	}
        
        
        
        
        
        protected function assertViewableItem($itemId)
	{
		$item = $this->em()->find('XenBulletins\BrandHub:Item', $itemId);

		if (!$item)
		{
			throw $this->exception($this->notFound(\XF::phrase('bh_requested_item_not_found')));
		}

		return $item;
	}
        
        
        public function actionReviews(ParameterBag $params)
	{

		$item = $this->assertViewableItem($params->item_id);


		$page = $this->filterPage();
		$perPage = $this->options()->bh_ReviewsPerPage;

		$ratingRepo = $this->repository('XenBulletins\BrandHub:ItemRating');
		$itemReviews = $ratingRepo->findReviewsInItem($item);
                
                $total = $itemReviews->total();             
		$this->assertValidPage($page, $perPage, $total, 'bh_brands/item/reviews');
		$itemReviews->limitByPage($page, $perPage);
                
		$itemReviews = $itemReviews->fetch();

		/** @var \XF\Repository\UserAlert $userAlertRepo */
//		$userAlertRepo = $this->repository('XF:UserAlert');
//		$userAlertRepo->markUserAlertsReadForContent('resource_rating', $reviews->keys());

		$viewParams = [
			'item' => $item,
			'itemReviews' => $itemReviews,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total
		];
		return $this->view('XenBulletins\BrandHub:Item\Reviews', 'bh_item_reviews', $viewParams);
	}
        
        
        protected function setupItemRate(\XenBulletins\BrandHub\Entity\Item $item)
	{

		$rater = $this->service('XenBulletins\BrandHub:Item\Rate', $item);

		$input = $this->filter([
			'rating' => 'uint',
			'is_anonymous' => 'bool'
		]);
                
                 $message = $this->plugin('XF:Editor')->fromInput('message');

		$rater->setRating($input['rating'], $message);


		return $rater;
	}

	public function actionRate(ParameterBag $params)
	{

		$visitorUserId = \XF::visitor()->user_id;

		$item = $this->assertViewableItem($params->item_id);

		if (!$item->canRate($error))
		{
			return $this->noPermission($error);
		}

		$existingRating = $item->ItemRatings[$visitorUserId];
		if ($existingRating && !$existingRating->canUpdate($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$rater = $this->setupItemRate($item);
			$rater->checkForSpam();

			if (!$rater->validate($errors))
			{
				return $this->error($errors);
			}

			$rating = $rater->save();

			return $this->redirect($this->buildLink('bh_brands/item/#reviews',$item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'existingRating' => $existingRating
			];
			return $this->view('XenBulletins\BrandHub:Item\Rate', 'bh_item_rate', $viewParams);
		}
	}
        
        
        
          public  function actionItemSub(ParameterBag $params){
          
             
          $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();
 
             $visitor = \XF::visitor();
             
          
               
            $itemSub = $this->em()->create('XenBulletins\BrandHub:ItemSub');
         
            $itemSub->user_id = $visitor->user_id;
            $itemSub->item_id = $params->item_id;
            $itemSub->save();
            
           return $this->redirect($this->buildLink('bh_brands/item',$item));
        
        
        
        
    }
    
    
     public function getRankRecord($item,$category_id) {

        $ItemPosition = [];

                 
        $ItemPosition['categoryItemDiscussionPosition'] = $this->categoryItemDiscussionPosition($item,$category_id);
        $ItemPosition['overallItemDiscussionPosition'] = $this->overallItemDiscussionPosition($item);



        $ItemPosition['categoryItemViewPosition'] = $this->categoryItemViewPosition($item,$category_id);

        $ItemPosition['overallItemViewPosition'] = $this->overallItemViewPosition($item);


     
        $ItemPosition['categoryItemReviewPosition'] = $this->categoryItemReviewPosition($item,$category_id);
        $ItemPosition['overallItemReviewPosition'] = $this->overallItemReviewPosition($item);


         $ItemPosition['totalcategoryItems'] = $this->totalcategoryItems($category_id);
         $ItemPosition['totalItems'] =$this->totalItems();
   

    
        return $ItemPosition;
    }
    
    
    public  function totalcategoryItems($category_id){
          
        $sql = "Select *, rank() over (order by discussion_count desc) as position  from bh_item  where category_id='$category_id';";
  
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
        $categoryTotal=count($result);
        
        return $categoryTotal;
        
    }
    
    public  function totalItems(){
        
        
        $sql = "Select *, rank() over (order by discussion_count desc) as position from bh_item;";
      
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
       
        $total = count($result);
         
        return $total;
        
    }



    public function categoryItemDiscussionPosition($item,$category_id){
        
        $sql = "Select *, rank() over (order by discussion_count desc) as position  from bh_item  where category_id='$category_id';";
  
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
        $position=$this->itemRankPosition($result,$item);
        
       
        
        
     
        return $position;
        
    }
    public function overallItemDiscussionPosition($item){
        
        $sql = "Select *, rank() over (order by discussion_count desc) as position from bh_item;";
      
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
        $position=$this->itemRankPosition($result,$item);
    
        return $position;
    }
    
    public function categoryItemViewPosition($item,$category_id){
        
        $sql = "Select *, rank() over (order by view_count desc) as position  from bh_item  where category_id='$category_id';";
  
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
        $position=$this->itemRankPosition($result,$item);
        
        return $position;
        
    }
    
    public function overallItemViewPosition($item){
       
        $sql = "Select *, rank() over (order by view_count desc) as position from bh_item;";
      
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        $position=$this->itemRankPosition($result,$item);
        
         return $position;
    }
    
    public  function categoryItemReviewPosition($item,$category_id){
        
         $sql = "Select *, rank() over (order by rating_avg desc) as position  from bh_item  where category_id='$category_id';";
  
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        
        $position=$this->itemRankPosition($result,$item);
        
        return $position;
    }
  
    public  function overallItemReviewPosition($item){
        
        $sql = "Select *, rank() over (order by rating_avg desc) as position from bh_item;";
      
        $db = \XF::db();
        $result=$db->query($sql)->fetchAll();
        $position=$this->itemRankPosition($result,$item);
        return $position;
        
    }
   
    
  

//    
    public function itemRankPosition($records, $item) {
        

     
        foreach ($records as $record) {



          

            if($item->item_id == $record['item_id']) {

               return $record['position'];
            
            }
        }

      
    }
    
    
      public  function actionitemThreads(ParameterBag $params){
        
        
       $threads = $this->finder('XF:Thread')->where('item_id', $params->item_id)->where('discussion_state','visible')->order('thread_id','DESC')->fetch();
        
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();
        
        $viewParams = [
            'threads' => $threads,
            'item'=> $item,
        
        ];
        
     return $this->view('XenBulletins\BrandHub:Brand', 'item_thread_lists', $viewParams);
        
        
    }
    
    
     public function actionFilmStripJump(ParameterBag $params) {

          
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();

        $direction = $this->filter('direction', 'str');
        $jumpFromId = $this->filter('attachment_id', 'uint');

        $jumpFrom = $this->finder('XF:Attachment')->where('attachment_id', $jumpFromId)->fetchOne();


        $filmStripPlugin = $this->plugin('XenBulletins\BrandHub:FilmStrip');

        $filmStripParams = $filmStripPlugin->getFilmStripParamsForJump($jumpFrom, $direction);

        $viewParams = [
            'item' => $item,
            'filmStripParams' => $filmStripParams
        ];

        return $this->view('XenBulletins\BrandHub:Item', 'bh_item_detail', $viewParams);
    }
    
    
    //*************************Edit item description***************************
    
      public function itemAddEdit($item) {

            $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData('bh_item', $item);
      
        $attachmentData["attachments"] = null;
        $viewParams = [
            'item' => $item,
            'attachmentData'=>$attachmentData,
        ];

        return $this->view('XenBulletins\BrandHub:Item', 'bh_item_description_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params) {
        $visitor = \XF::visitor();
        if(!$visitor->hasPermission('bh_brand_hub', 'bh_can_edit_itemDescript'))
        {
            throw $this->exception($this->noPermission());
        }
                   
        $item = $this->assertItemExists($params->item_id, 'Description');

        return $this->itemAddEdit($item);
    }
    
    
    protected function saveDescription(\XenBulletins\BrandHub\Entity\Item $item) {
        $message = $this->plugin('XF:Editor')->fromInput('description');
        
           if (strcmp($message, $item->Description->description)) {

               
                $detail = " Description";
                $this->descriptionitemNotify($item,$detail);
            }


        $descEntity = $this->finder('XenBulletins\BrandHub:ItemDescription')->where('item_id', $item->item_id)->fetchOne();
        if (!$descEntity) {
            $descEntity = $this->em()->create('XenBulletins\BrandHub:ItemDescription');
        }

        $descEntity->description = $message;
        $descEntity->item_id = $item->item_id;
        $descEntity->save();

        return $descEntity;
    }
    
    
     public function descriptionitemNotify($item,$detail){
        
          $requests = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $item->item_id)->with(['User', 'Item'])->fetch();
      
            if ($detail != '') {
                
                foreach ($requests as $request) {

                    $link = $this->app->router('public')->buildLink('bh_brands/item', $request->Item);
                  
 
                    \XenBulletins\BrandHub\Helper::updateItemNotificiation($request->Item->item_title, $link, $detail, $request->User);
                }
            }
        
    }
    
    
    
     public function actionSave(ParameterBag $params) {

        $this->assertPostOnly();

        if ($params->item_id) {
            $item = $this->assertItemExists($params->item_id);
        } else {
            $item = $this->em()->create('XenBulletins\BrandHub:Item');
        }

        $descEntity = $this->saveDescription($item);


        $item->save();
        
         $hash = $this->filter('attachment_hash', 'str');
        $sql = "Update xf_attachment set content_id=$item->item_id where temp_hash='$hash'";
        $db = \XF::db();
        $db->query($sql);
        
          $attachments = $this->finder('XF:Attachment')->where('temp_hash', $hash)->fetch();
        foreach ($attachments as $attachment) {
            $attachment->temp_hash = '';
            $attachment->unassociated = 0;
            $attachment->save();
        }

        return $this->redirect($this->buildLink('bh_brands/item', $item));
    }
    
    
    protected function assertItemExists($id, $with = null, $phraseKey = null) {
        return $this->assertRecordExists('XenBulletins\BrandHub:Item', $id, $with, $phraseKey);
    }

//*********************************************************************************
    
    
    
  public function actionUploadPhoto(ParameterBag $params)
    {
        if (!\xf::visitor()->hasPermission('bh_brand_hub', 'bh_canUploadPhotos'))
        {
                return $this->noPermission();
        }
        

         $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();
         $brandId = $item->Brand->brand_id; 

        $items = $this->Finder('XenBulletins\BrandHub:Item')->where('brand_id', $brandId)->order('item_id', 'DESC')->fetch();



        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData('bh_item', $item);
        
        $attchmentTime=$attachmentData['attachments'] ? end($attachmentData['attachments'])->attach_date : '';
       $attachmentData["attachments"] = null;
        

        $viewParams = [
            'attachment_time' => $attchmentTime,
            'selectedItem' => $item,
            'items' => $items,
            'attachmentData' => $attachmentData,
        ];

//            var_dump($viewParams);exit;

        return $this->view('XenBulletins\BrandHub:Item', 'bh_uploadPhoto', $viewParams);
    }
    
    public function actionSavePhoto()
    {
        $userId = \xf::visitor()->user_id;
        
        $itemId = $this->filter('item_id','UINT');
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $itemId)->fetchOne();

        
        
        $itemPage = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $itemId)->where('user_id', $userId)->fetchOne();
      
        $hash = $this->filter('attachment_hash', 'str');


        $sql = "Update xf_attachment set content_id=$item->item_id where temp_hash='$hash'";
        $db = \XF::db();
        $db->query($sql);

        
        $attachments = $this->finder('XF:Attachment')->where('temp_hash', $hash)->fetch();
        foreach ($attachments as $attachment) {
            $attachment->temp_hash = '';
            $attachment->unassociated = 0;
            $attachment->user_id = $userId;
            $attachment->page_id = $itemPage ? $itemPage->page_id : 0;
            $attachment->save();
        }
        
             $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData('bh_item', $item);
            if(count($attachmentData['attachments'])>0){
            $attachmentTime = end($attachmentData['attachments'])->attach_date;

            }
            
              if (count($attachmentData['attachments'])>0 && $attachmentTime > $this->filter('attachment_time', 'int')) {
                 $detail="";
                $detail = $detail . " New photos";
                
                $this->itemAttachmentNofity($item,$detail);
        
                if($itemPage){
                    
                $this->ownerpageAttachmentNotify($itemPage,$detail);
                }
            }
        
        return $this->redirect($this->buildLink('bh_brands/item', $item));
        
    }
    
    
    public function itemAttachmentNofity($item,$detail){
        
          $requests = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $item->item_id)->with(['User', 'Item'])->fetch();
      
            if ($detail != '') {
                
                foreach ($requests as $request) {

                    $link = $this->app->router('public')->buildLink('bh_brands/item', $request->Item);
                  
 
                    \XenBulletins\BrandHub\Helper::updateItemNotificiation($request->Item->item_title, $link, $detail, $request->User);
                }
            }
        
        
    }
    
    
    public function ownerpageAttachmentNotify($itemPage,$detail) {
   
     
        $requests = $this->finder('XenBulletins\BrandHub:PageSub')->where('page_id', $itemPage->page_id)->with(['User'])->fetch();

        if ($detail != '') {


            foreach ($requests as $request) {

                $link = $this->app->router('public')->buildLink('bh_item/ownerpage/page', $request->Page);
              
                \XenBulletins\BrandHub\Helper::updatePageNotificiation($itemPage->Item->item_title, $link, $detail, $request->User);
            }
        }
    }

        
    public function assertLoggedIn()
    {
        if (!$this->isLoggedIn())
        {
                throw $this->exception($this->noPermission());
        }
    }
    
       public  function actionmainPhoto(ParameterBag $params){
       

      $items = $this->finder('XF:Attachment')->where('content_id', $params->item_id)->where('content_type', 'bh_item')->order('attach_date','Desc')->fetch();
      
         if(!count($items)){
          
           $phraseKey = "No photo in Item";
                                throw $this->exception(
                                        $this->notFound(\XF::phrase($phraseKey))
                                );
      }
      $selectedAttachment = $this->finder('XF:Attachment')->where('content_id', $params->item_id)->where('content_type', 'bh_item')->where('item_main_photo', 1)->order('attach_date','Desc')->fetchOne();
 
//      var_dump($selectedAttachment);exit;
      $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();

            $viewParams = [
            'items' => $items,
             'item'=>$item,
             'selectedAttachment'=>$selectedAttachment,
        ];
            
        return $this->view('XenBulletins\BrandHub:Item', 'bh_item_main_photo', $viewParams);
       }
       
       public function actionsetMainPhoto(ParameterBag $params){
           
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();
        $mainItem = $this->filter('mainitem', 'int');
       
       if($mainItem){
           
        $sql = "Update xf_attachment set item_main_photo=0 where content_id='$params->item_id'";
        $db = \XF::db();
        $db->query($sql);
        
     

         $attachment = $this->finder('XF:Attachment')->where('attachment_id', $mainItem)->fetchOne();
          
         $attachment->fastUpdate('item_main_photo',1);
                 
         $attachment->save();
           
         }
         return $this->redirect($this->buildLink('bh_brands/item', $item));
           
       }
       
       
       public function actionBookmark(ParameterBag $params)
	{
           if (!\xf::visitor()->hasPermission('bh_brand_hub', 'create_bookmark') || !\xf::visitor()->user_id)
             {
                 return $this->noPermission();
             }
       
		$item = $this->assertViewableItem($params->item_id);


		/* @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');

//                var_dump($bookmarkPlugin);exit;
		return $bookmarkPlugin->actionBookmark(
			$item, $this->buildLink('bh_brands/item/bookmark', $item)
		);
	}
        
         public function actionReact(ParameterBag $params)
	{
             if (!\xf::visitor()->hasPermission('bh_brand_hub', 'react') || !\xf::visitor()->user_id)
             {
                 return $this->noPermission();
             }
		$item = $this->assertViewableItem($params->item_id);

		$reactionPlugin = $this->plugin('XF:Reaction');
            return	 $reactionPlugin->actionReactSimple($item, 'bh_brands/item');
                 
	}
        
        public function actionReactions(ParameterBag $params)
	{
	
            
                $item = $this->assertViewableItem($params->item_id);


		$breadcrumbs = $item->getBreadcrumbs();
             
		$title = \XF::phrase('members_who_reacted_to_message_x', ['position' => ($item->position + 1)]);
               

		/* @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$item,
			'bh_brands/item/reactions',
			$title, $breadcrumbs
		);
	}
        
        
        
         public  function actionUnSub(ParameterBag $params){

        

          $item = $this->assertItemExists($params->item_id);
                   $viewParams = [
				'item' => $item,
				
			];
                   
		return $this->view('XenBulletins\BrandHub:Unsub', 'delete_confirm_unsub', $viewParams);
    }
    
     public  function actionUnSubItem(ParameterBag $params){
        
         
          $itemUnSub = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $params->item_id)->where('user_id', \xf::visitor()->user_id)->fetchOne();
       
        if($itemUnSub->user_id != \xf::visitor()->user_id)
        {
            return $this->noPermission();
        }
             $item = $this->assertItemExists($params->item_id);

              if($itemUnSub){

                  $itemUnSub->delete();
              }


               return $this->redirect($this->buildLink('bh_brands/item',$item));
        
    }
        
        

}