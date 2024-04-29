<?php

namespace XenBulletins\BrandHub\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;

class OwnerPage extends AbstractController {
    
      protected function preDispatchController($action, ParameterBag $params) {
          
        if ($action == 'Add' || $action == 'Pagesub')
        {
            $this->assertLoggedIn();
        }
        
    }
    
    
    public function actionIndex(ParameterBag $params) {

        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $params->item_id)->fetchOne();
        $itemPages = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $params->item_id);

        $type = $this->filter('type', 'str');
        if ($type) {
            $itemPages->order($type, 'DESC');
            $pageSelected = $type;
        }
        else
        {
            $itemPages->order('page_id', 'DESC');
            $pageSelected = 'all';
        }
        
//        $itemPages = $itemPages->fetch();
        

            $viewParams = [

                'itemPages' => $itemPages->fetch(),
                'page' => $itemPages->fetchOne(),
                'ownerPageTotal' => count($itemPages->fetch()),
                'pageSelected' => $pageSelected,
                'item' => $item,
            ];

            return $this->view('XenBulletins\BrandHub:OwnerPage', 'bh_item_owner_page_all', $viewParams);
            

    }

    public function actionPage(ParameterBag $params) {


        $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('page_id', $params->page_id)->fetchOne();


        $page->view_count += 1;
        $page->save();
       

        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $page->item_id)->fetchOne();

        $attachmentData = $this->finder('XF:Attachment')->where('content_type', 'bh_item')->where('page_id', $page->page_id)->where('page_main_photo', 1)->order('attach_date','Desc')->fetchOne();
       
        if(!$attachmentData){
            
        $attachmentData = $this->finder('XF:Attachment')->where('content_type', 'bh_item')->where('page_id', $page->page_id)->order('attach_date','Desc')->fetchOne();
        
        }
     
        $attachment_id = $this->filter('attachment_id', 'STR');

        $attachmentItem = "";

        $filmStripPluginlist = "";

        $filmStripPlugin = $this->plugin('XenBulletins\BrandHub:FilmStrip');

        if ($attachment_id) {

            $attachmentItem = $this->assertViewableAttachmentItem($attachment_id);
            $filmStripPluginlist = $filmStripPlugin->getFilmStripParamsForView($attachmentItem, $page);
        } elseif ($attachmentData) {



            $filmStripPluginlist = $filmStripPlugin->getFilmStripParamsForView($attachmentData,$page);
        }


        $discussions = $this->finder('XF:Thread')->where('item_id', $page->item_id)->where('user_id', $page->user_id)->where('discussion_state','visible')->order('thread_id','DESC')->fetch(\xf::options()->bh_discussions_on_item);

        $alreadySub = $this->finder('XenBulletins\BrandHub:PageSub')->where('page_id', $params->page_id)->where('user_id', \XF::visitor()->user_id)->fetchOne();

        $pagePosition=$this->getRankRecord($page);
        
        $viewParams = [
            'filmStripParams' => $filmStripPluginlist,
            'mainItem' => $attachment_id ? $attachmentItem : $attachmentData,
            'page' => $page,
            'item' => $item,
            'discussions' => $discussions,
            'alreadySub' => $alreadySub,
            'pagePosition' => $pagePosition
        ];
        return $this->view('XenBulletins\BrandHub:Brand', 'bh_page_detail', $viewParams);
    }

    public function itemAddEdit($item,$Page) {

        
//        $attachmentRepo = $this->repository('XF:Attachment');
//        $attachmentData = $attachmentRepo->getEditorData('bh_ownerpage', $Page);

   
        if($Page->item_id){
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $Page->item_id)->fetchOne();
        }

        $viewParams = [
            'Page' => $Page,
//            'attachment_time' => $attachmentData['attachments'] ? end($attachmentData['attachments'])->attach_date : '',
            'item' => $item,
//            'attachmentData' => $attachmentData,                    
        ];

        return $this->view('XenBulletins\BrandHub:OwnerPage', 'bh_item_page_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params) {

        $visitor = \XF::visitor();
        
        $Page = $this->assertOwnerpageExists($params->page_id, 'Detail');
        
         if(!$visitor->hasPermission('bh_brand_hub', 'bh_can_edit_ownerpage') || $visitor->user_id!=$Page->user_id)
        {
            throw $this->exception($this->noPermission());
        }

        return $this->itemAddEdit("",$Page);
    }

    public function actionAdd(ParameterBag $params) {
      
        $item_id = $this->filter('item', 'STR');
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $item_id)->fetchOne();

         $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $item_id)->where('user_id', \xf::visitor()->user_id)->fetchOne();
            if($page)
            {
                $phraseKey = "Owner page already Created in ".$page->Item->item_title." Item";
                            throw $this->exception(
                                    $this->notFound(\XF::phrase($phraseKey))
                            );
            }
        $itemPage = $this->em()->create('XenBulletins\BrandHub:OwnerPage');
        
        

        return $this->itemAddEdit($item, $itemPage);
    }

//************************Save category**********************************************

    protected function saveDescription(\XenBulletins\BrandHub\Entity\OwnerPage $OwnerPage) {

        $about = $this->plugin('XF:Editor')->fromInput('about');

        $attachment = $this->plugin('XF:Editor')->fromInput('attachment');
        $customizations = $this->plugin('XF:Editor')->fromInput('customizations');

        $PageDetail = $this->finder('XenBulletins\BrandHub:PageDetail')->where('page_id', $OwnerPage->page_id)->fetchOne();
        if (!$PageDetail) {
            $PageDetail = $this->em()->create('XenBulletins\BrandHub:PageDetail');
        }

        $PageDetail->about = $about;
        $PageDetail->attachment = $attachment;
        $PageDetail->customizations = $customizations;
        $PageDetail->page_id = $OwnerPage->page_id;
        $PageDetail->save();

        return $PageDetail;
    }

    protected function pageSaveProcess(\XenBulletins\BrandHub\Entity\OwnerPage $pageOwner) {
        $form = $this->formAction();

        $input = $this->filter(['item_id' => 'STR']);
        
        
        if ($pageOwner->isInsert()) {
                $input['user_id']=\xf::visitor()->user_id;



                $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('item_id', $input['item_id'])->where('user_id', \xf::visitor()->user_id)->fetchOne();
                if($page)
                {
                    $phraseKey = "Owner page already Created in ".$page->Item->item_title." Item";
                                throw $this->exception(
                                        $this->notFound(\XF::phrase($phraseKey))
                                );
                }
                
           

        }
        
        
                

        if ($pageOwner->isUpdate()) {

            $hash = $this->filter('attachment_hash', 'str');

            $sql = "Update xf_attachment set content_id=$pageOwner->page_id where temp_hash='$hash'";
            $db = \XF::db();
            $db->query($sql);
            $detail = "";
            $link = "";

            $requests = $this->finder('XenBulletins\BrandHub:PageSub')->where('page_id', $pageOwner->page_id)->with(['User'])->fetch();

            if (strcmp($this->plugin('XF:Editor')->fromInput('about'), $pageOwner->Detail->about)) {

                $detail = $detail . "About";
            }
            if (strcmp($this->plugin('XF:Editor')->fromInput('attachment'), $pageOwner->Detail->attachment)) {

                $detail = $detail . " Attachment";
            }

            if (strcmp($this->plugin('XF:Editor')->fromInput('customizations'), $pageOwner->Detail->customizations)) {

                $detail = $detail . " Customizations";
            }


            if ($detail != '') {


                foreach ($requests as $request) {

                    $link = $this->app->router('public')->buildLink('bh_item/ownerpage', $request->Page);

                    \XenBulletins\BrandHub\Helper::updatePageNotificiation($pageOwner->Item->item_title, $link, $detail, $request->User);
                }
            }
        }




        $form->basicEntitySave($pageOwner, $input);

        return $form;
    }

    public function actionSave(ParameterBag $params) {
        $this->assertPostOnly();

        if ($params->page_id) {
            $pageOwner = $this->assertOwnerPageExists($params->page_id, 'Detail');
        } else {
            $pageOwner = $this->em()->create('XenBulletins\BrandHub:OwnerPage');
        }
        
        
        

        $this->pageSaveProcess($pageOwner)->run();

        $pagedetailEntity = $this->saveDescription($pageOwner);


  $sql = "Update xf_attachment set page_id=$pageOwner->page_id where content_id=$pageOwner->item_id and user_id=$pageOwner->user_id";
        $db = \XF::db();
        $db->query($sql);



        $this->pageCount($pageOwner);
       
        $threads = $this->finder('XF:Thread')->where('item_id', $pageOwner->item_id)->where('user_id', \xf::visitor()->user_id)->fetch();
        $pageOwner->discussion_count = count($threads);
        $pageOwner->save();
        

          return $this->redirect($this->buildLink('bh_item/ownerpage/page',$pageOwner));
    }



    protected function assertOwnerPageExists($id, $with = null, $phraseKey = null) {
        return $this->assertRecordExists('XenBulletins\BrandHub:OwnerPage', $id, $with, $phraseKey);
    }



    protected function assertViewableAttachmentItem($attachmentId) {


        $attachmentitem = $this->em()->find('XF:Attachment', $attachmentId);

        if (!$attachmentitem) {
            throw $this->exception($this->notFound(\XF::phrase('xfmg_requested_media_item_not_found')));
        }


        return $attachmentitem;
    }

  

    public function actionPageSub(ParameterBag $params) {

        $visitor = \XF::visitor();
 
        $pageSub = $this->em()->create('XenBulletins\BrandHub:PageSub');
    
        $pageSub->user_id = $visitor->user_id;
        $pageSub->page_id = $params->page_id;
        $pageSub->save();

        return $this->redirect($this->buildLink('bh_item/ownerpage/'.$params->page_id.'/'.$params->item_id.'/page'));
    }
    
    
      public  function getRankRecord($page){
            
              $pagePosition=[];
        
     
              
              
              $overallPagesDiscussion = $this->finder('XenBulletins\BrandHub:OwnerPage')->order('discussion_count', 'DESC')->fetch();
              $overallPagesView = $this->finder('XenBulletins\BrandHub:OwnerPage')->order('view_count', 'DESC')->fetch();
              
              $overallPagesFollow = $this->finder('XenBulletins\BrandHub:PageCount')->order('follow_count', 'DESC')->fetch();
              
              $overallPagesAtachment = $this->finder('XenBulletins\BrandHub:PageCount')->order('attachment_count', 'DESC')->fetch();
              

              
              $pagePosition['pageDiscussionPosition']=$this->pageRankPosition($overallPagesDiscussion,$page);
            
              $pagePosition['pageViewPosition']=$this->pageRankPosition($overallPagesView, $page);

              $pagePosition['pageFollowPosition']=$this->pageRankPosition($overallPagesFollow, $page);
              
              $pagePosition['pageAttachmentPosition']=$this->pageRankPosition($overallPagesAtachment, $page);
              

              return $pagePosition;

       
    }
    
    
    public function pageRankPosition($records, $checkPosition) {

      
        $position = 0;

        foreach ($records as $record) {



            $position = $position + 1;

            if ($record->page_id == $checkPosition->page_id) {

                break;
            }
        }
        
        

        return $position;
    }
    
    
     public function pageCount($OwnerPage) {


        $PageCount = $this->finder('XenBulletins\BrandHub:PageCount')->where('page_id', $OwnerPage->page_id)->fetchOne();
        
        $follow_count=$this->finder('XenBulletins\BrandHub:PageSub')->where('page_id',$OwnerPage->page_id)->fetch();
        
      
        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData('bh_ownerpage', $OwnerPage);
       
        if (!$PageCount) {
            $PageCount = $this->em()->create('XenBulletins\BrandHub:PageCount');
        }

        $PageCount->follow_count = count($follow_count);
        $PageCount->attachment_count = count($attachmentData['attachments']);
        $PageCount->page_id=$OwnerPage->page_id;
        $PageCount->save();
        
        return $PageCount;
    }
    
    
    public  function actionPageThreads(ParameterBag $params){
        
        $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('page_id', $params->page_id)->fetchOne();

        $threads = $this->finder('XF:Thread')->where('item_id', $page->item_id)->where('user_id', $page->user_id)->where('discussion_state','visible')->order('thread_id','DESC')->fetch();
        $item = $this->finder('XenBulletins\BrandHub:Item')->where('item_id', $page->item_id)->fetchOne();
        $viewParams = [
            'threads' => $threads,
            'page'=> $page,
            'item' => $item
        
        ];
        
     return $this->view('XenBulletins\BrandHub:Brand', 'ownerPage_thread_lists', $viewParams);
    }
    
    public function actionFilmStripJump(ParameterBag $params) {

      
          
        $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('page_id', $params->page_id)->fetchOne();

        $direction = $this->filter('direction', 'str');
        $jumpFromId = $this->filter('attachment_id', 'uint');

        $jumpFrom = $this->finder('XF:Attachment')->where('attachment_id', $jumpFromId)->fetchOne();

        $filmStripPlugin = $this->plugin('XenBulletins\BrandHub:FilmStrip');

        $filmStripParams = $filmStripPlugin->getFilmStripParamsForJump($jumpFrom, $direction, $page);

        $viewParams = [
            'page' => $page,
            'filmStripParams' => $filmStripParams
        ];

        return $this->view('XenBulletins\BrandHub:Item', 'bh_page_detail', $viewParams);
    }
    
    public function assertLoggedIn()
    {
        if (!$this->isLoggedIn())
        {
                throw $this->exception($this->noPermission());
        }
    }
    
    
    public  function actionmainPhoto(ParameterBag $params){
       

      
      $pageAttachments = $this->finder('XF:Attachment')->where('content_type', 'bh_item')->where('page_id', $params->page_id)->order('attach_date','Desc')->fetch();
        
      if(!count($pageAttachments)){
          
           $phraseKey = "No photo in Onwer page";
                                throw $this->exception(
                                        $this->notFound(\XF::phrase($phraseKey))
                                );
      }
      $selectedAttachment = $this->finder('XF:Attachment')->where('content_type', 'bh_item')->where('page_id', $params->page_id)->where('page_main_photo', 1)->order('attach_date','Desc')->fetchOne();
       

      $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('page_id', $params->page_id)->fetchOne();

       
        $viewParams = [
            'pageAttachments' => $pageAttachments,
            'page'=>$page,
            'selectedAttachment'=>$selectedAttachment,
            
        ];
            
        return $this->view('XenBulletins\BrandHub:Item', 'bh_page_main_photo', $viewParams);
       }
       
         public function actionsetMainPhoto(ParameterBag $params){
          
        $page = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('page_id', $params->page_id)->fetchOne();
           
        $mainItem = $this->filter('mainitem', 'int');

        if($mainItem){
            
                    $sql = "Update xf_attachment set page_main_photo=0 where page_id='$params->page_id'";

                    $db = \XF::db();
                    $db->query($sql);

                     $attachment = $this->finder('XF:Attachment')->where('attachment_id', $mainItem)->fetchOne();

                     $attachment->fastUpdate('page_main_photo',1);

                     $attachment->save();
        }
          return $this->redirect($this->buildLink('bh_item/ownerpage/page',$page));
           
           
       }
       
       
         public function actionReact(ParameterBag $params)
	{
             if (!\xf::visitor()->hasPermission('bh_brand_hub', 'react_page') || !\xf::visitor()->user_id)
             {
                 return $this->noPermission();
             }
		$page = $this->assertOwnerPageExists($params->page_id);


		$reactionPlugin = $this->plugin('XF:Reaction');
            return	 $reactionPlugin->actionReactSimple($page, 'bh_item/ownerpage');
                 
	}
        
        public function actionReactions(ParameterBag $params)
	{
	
            
                $page = $this->assertOwnerPageExists($params->page_id);



		$breadcrumbs = $page->getBreadcrumbs();
             
		$title = \XF::phrase('members_who_reacted_to_message_x', ['position' => ($page->position + 1)]);
               

		/* @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$page,
			'bh_item/ownerpage/reactions',
			$title, $breadcrumbs
		);
	}
    
    
    
    
 
}