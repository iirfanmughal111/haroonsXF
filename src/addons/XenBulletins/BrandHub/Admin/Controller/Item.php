<?php

namespace XenBulletins\BrandHub\Admin\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;

class Item extends AbstractController {

    public function actionIndex() {
        $page = $this->filterPage();
        $perPage = 20;

        $items = $this->Finder('XenBulletins\BrandHub:Item')->with('Category')->with('Brand');

        $total = $items->total();
        $this->assertValidPage($page, $perPage, $total, 'bh_brand');
        $items->limitByPage($page, $perPage);

        $viewParams = [
            'items' => $items->order('item_id', 'DESC')->fetch(),
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ];

        return $this->view('XenBulletins\BrandHub:Brand', 'bh_items', $viewParams);
    }

    public function itemAddEdit($item) {

//                    $nodeTree = $this->getNodeTree();
        $brands = $this->Finder('XenBulletins\BrandHub:Brand')->order('brand_id', 'DESC')->fetch();
        $brandCategories = $this->Finder('XenBulletins\BrandHub:Category')->order('category_id', 'DESC')->fetch();

        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData('bh_item', $item);


        $viewParams = [
            'item' => $item,
            'attachmentData' => $attachmentData,
            'attachment_time' => $attachmentData['attachments'] ? end($attachmentData['attachments'])->attach_date : '',
            'brands' => $brands,
            'brandCategories' => $brandCategories,
        ];

        return $this->view('XenBulletins\BrandHub:Item', 'bh_item_add_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params) {
        $item = $this->assertItemExists($params->item_id, 'Description');

        return $this->itemAddEdit($item);
    }

    public function actionAdd() {
        $item = $this->em()->create('XenBulletins\BrandHub:Item');

        return $this->itemAddEdit($item);
    }

//************************Save category**********************************************

    protected function saveDescription(\XenBulletins\BrandHub\Entity\Item $item) {
        $message = $this->plugin('XF:Editor')->fromInput('description');

        $descEntity = $this->finder('XenBulletins\BrandHub:ItemDescription')->where('item_id', $item->item_id)->fetchOne();
        if (!$descEntity) {
            $descEntity = $this->em()->create('XenBulletins\BrandHub:ItemDescription');
        }

        $descEntity->description = $message;
        $descEntity->item_id = $item->item_id;
        $descEntity->save();

        return $descEntity;
    }

    protected function itemSaveProcess(\XenBulletins\BrandHub\Entity\Item $item) {
        $form = $this->formAction();

         $input = $this->filter([
            'item_title' => 'STR',
            'brand_id' => 'UINT',
            'category_id' => 'UINT',
            'make' => 'STR',
            'model' => 'STR',
            'user_id'=>'STR',
        ]);
        
        $input['user_id']=\XF::visitor()->user_id;
        
        if (!$input['brand_id']) {
            $this->validateItem('Brand');
        }
        if (!$input['category_id']) {
            $this->validateItem('Category');
        }



        if ($item->isUpdate()) {
          
            
        $hash = $this->filter('attachment_hash', 'str');

        $sql = "Update xf_attachment set content_id=$item->item_id where temp_hash='$hash'";
        $db = \XF::db();
        $db->query($sql);
        
            $detail = "";
            $link = "";

            $requests = $this->finder('XenBulletins\BrandHub:ItemSub')->where('item_id', $item->item_id)->with(['User', 'Item'])->fetch();

            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData('bh_item', $item);
            if(count($attachmentData['attachments'])>0){
            $attachmentTime = end($attachmentData['attachments'])->attach_date;

            }

            if (strcmp($input['item_title'], $item->item_title)) {

                $detail = " Title";
            }
            if (strcmp($input['make'], $item->make)) {

                $detail = $detail . " Make";
            }

            if (strcmp($input['model'], $item->model)) {

                $detail = $detail . " Model";
            }
            if (strcmp($this->plugin('XF:Editor')->fromInput('description'), $item->Description->description)) {

                $detail = $detail . " Description";
            }

            if (count($attachmentData['attachments'])>0 && $attachmentTime > $this->filter('attachment_time', 'int')) {

                $detail = $detail . " New photos";
            }
            
             $spec=$this->updateSpecificationNotify($item);

            if($spec==1){
                  $detail = $detail . " Specificatons";
            }


            if ($detail != '') {
                foreach ($requests as $request) {

                    $link = $this->app->router('public')->buildLink('bh_brands/item', $request->Item);
                  
                    \XenBulletins\BrandHub\Helper::updateItemNotificiation($request->Item->item_title, $link, $detail, $request->User);
                }
            }
        }





        $itemName = $this->actionFind($input['item_title']);

        if (!$itemName) {
            $form->basicEntitySave($item, $input);

            return $form;
        } else {
            if ($item->item_id == $itemName->item_id) {






                $form->basicEntitySave($item, $input);

                return $form;
            } else {
                $phraseKey = $itemName->item_title . " item already exists.";
                throw $this->exception(
                                $this->notFound(\XF::phrase($phraseKey))
                );
            }
        }
    }

    public function actionSave(ParameterBag $params) {

        $this->assertPostOnly();

        if ($params->item_id) {
            $item = $this->assertItemExists($params->item_id,'Description');
        } else {
            $item = $this->em()->create('XenBulletins\BrandHub:Item');
        }

        $this->itemSaveProcess($item)->run();

        $descEntity = $this->saveDescription($item);

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



        $customFields = $this->filter('item.custom_fields', 'array');

        $fieldSet = $item->custom_fields;

        $fieldDefinition = $fieldSet->getDefinitionSet()
                ->filterEditable($fieldSet, 'user');

        $customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

        if ($fieldDefinition) {

            $fieldSet->bulkSet($customFields, $customFieldsShown, 'user', true);
        }

        $item->save();

        return $this->redirect($this->buildLink('bh_item'));
    }

//*********************************************************************************


      public function actionDelete(ParameterBag $params) {

        $item = $this->assertItemExists($params->item_id);

        if($this->isPost())
        {               
              $attachments = $this->finder('XF:Attachment')->where('content_type', 'bh_item')->where('content_id',$params->item_id)->fetch();
           
              if(count($attachments)){
                  
                    foreach($attachments as $attachment){

                  $path = \XF::getRootDirectory(). $this->getAbstractDepositAttachmentPath($attachment->Data->file_hash,$attachment->attachment_id);
             
                       if(file_exists($path))
                        { 
                           
                           $this->App()->fs()->delete($this->getAbstractCustomAttachmentPath($attachment->Data->file_hash,$attachment->attachment_id));
                        
          
                   }
                        $attachment->delete();
                        
                     
                    }
              
              }
         
            $description = $this->finder('XenBulletins\BrandHub:ItemDescription')->where('item_id',$params->item_id)->fetchOne();
            $description->delete();

        }
      
        $plugin = $this->plugin('XF:Delete');

        return $plugin->actionDelete(
                        $item,
                        $this->buildLink('bh_item/delete', $item),
                        $this->buildLink('bh_item/edit', $item),
                        $this->buildLink('bh_item'),
                        $item->item_title
        );
    }
    public function getAbstractDepositAttachmentPath($hash,$id) 
        {
            $path=sprintf('/data/attachments/0/'.$id.'-'.$hash.'.jpg');
            return $path;
        }
    public function getAbstractCustomAttachmentPath($hash,$id) 
        {
            $path=sprintf('data://attachments/0/'.$id.'-'.$hash.'.jpg');
          
            return $path;
             
        }

    protected function assertItemExists($id, $with = null, $phraseKey = null) {
        return $this->assertRecordExists('XenBulletins\BrandHub:Item', $id, $with, $phraseKey);
    }

    public function actionFind($title) {

        $itemName = $this->finder('XenBulletins\BrandHub:Item')->where('item_title', $title)->fetchOne();
        return $itemName;
    }

    protected function getNodeTree() {
        $nodeRepo = \XF::repository('XF:Node');
        $nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());

        // only list nodes that are forums or contain forums
        $nodeTree = $nodeTree->filter(null, function ($id, $node, $depth, $children, $tree) {
            return ($children || $node->node_type_id == 'Forum');
        });

        return $nodeTree;
    }

    protected function validateItem($type) {
        throw $this->exception(
                        $this->notFound(\XF::phrase('please_select_a_x', ['type' => $type]))
        );
    }
    
     
     public function updateSpecificationNotify($item){
      
        $customFields = $this->filter('item.custom_fields', 'array');
         
        if($customFields){
                  $sepcification=0;
                  $fieldSet=[];
                   $keys=array_keys($customFields);

                 

                   foreach($keys as $key){

                                  if($item->custom_fields->$key){
                                      

                                  $fieldSet[$key]= $item->custom_fields->$key;

                                   if(is_array($customFields[$key]) && is_array($item->custom_fields->$key)){
                                        
//                                       var_dump($key);
//                                       var_dump($customFields[$key]);
                                     

                                         
                                          foreach($customFields[$key] as $field){
                                              

                                              
                                                    if(in_array($field,$item->custom_fields->$key)){
                                                           
//                                                               var_dump($key);
//                                              var_dump($item->custom_fields->$key);exit;
                                                            if(strcmp($field, $item->custom_fields->$key[$field])){


                                                                 $sepcification=1; 
                                                            }

                                                    }else{
                                                        
                                                        
                                                                 $sepcification=1; 
                                                        
                                                    } 
                                          
                                             
                                             
                                          }
                                        
                                       
                                    }
                                    
                                    
                                 
                                 elseif(strcmp($customFields[$key],$item->custom_fields->$key)){
                                   
                                   
                              
                                     $sepcification=1;
                                 }
        
                                
                       }
                    }
                   

                  if(count($customFields)> count($fieldSet)){

                      $sepcification=1;

                  }
                  
                

            }
            
            return $sepcification;
    }
   
   

}