<?php

namespace XenBulletins\BrandHub\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XenBulletins\BrandHub\Entity;
use XF\Mvc\ParameterBag;


class Brands extends AbstractController
{
     
       public function actionIndex()
       {
          
            $page = $this->filterPage();
            $perPage = 20;

            $brands = $this->Finder('XenBulletins\BrandHub:Brand');
            

            $total = $brands->total();
            $this->assertValidPage($page, $perPage, $total, 'bh_brands');
            $brands->limitByPage($page, $perPage);
            
            
            $type = $this->filter('type', 'str');
            if ($type) {
                $brands->order($type, 'DESC');
                $pageSelected = $type;
            }
            else
            {
                $brands->order('brand_id', 'DESC');
                $pageSelected = 'all';
            }

            $viewParams = [

                    'brands' => $brands->fetch(),
                    'pageSelected' => $pageSelected,
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('XenBulletins\BrandHub:Brand', 'bh_brand_list', $viewParams);
       }
       
       public function actionBrand(ParameterBag $params)
       {
          
            $page = $this->filterPage();
            $perPage = 20;
            
            $brandObj = $this->Finder('XenBulletins\BrandHub:Brand')->with('Description')->where('brand_id',$params->brand_id);
            
            $items = $this->Finder('XenBulletins\BrandHub:Item')->where('brand_id',$params->brand_id);
            

            $total = $items->total();
            $this->assertValidPage($page, $perPage, $total, 'bh_brands/brand-items');
            $items->limitByPage($page, $perPage);
            
            
            $type = $this->filter('type', 'str');
            if ($type) {
                $items->order($type, 'DESC');
                $pageSelected = $type;
            }
            else
            {
                $items->order('brand_id', 'DESC');
                $pageSelected = 'all';
            }

            $viewParams = [

                    'items' => $items->fetch(),
                    'brandObj' => $brandObj->fetchOne(),
                    'pageSelected' => $pageSelected,
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('XenBulletins\BrandHub:Brand', 'bh_item_list', $viewParams);
       }
       
       public function actionItem(ParameterBag $item)
       {
            return $this->noPermission();
       }
       
//*****************Edit Brand Description***************************************
       
        public function brandAddEdit($brand)
        {

            $viewParams = [
                'brand' => $brand
            ];

            return $this->view('XenBulletins\BrandHub:Category', 'bh_brand_description_edit', $viewParams);
        }

        public function actionEdit(ParameterBag $params)
        {
            $visitor = \XF::visitor();
           if(!$visitor->hasPermission('bh_brand_hub', 'bh_can_edit_brandDescript'))
           {
               throw $this->exception($this->noPermission());
           }
            $brand = $this->assertBrandExists($params->brand_id,'Description');

            return $this->brandAddEdit($brand);
        }
        
        protected function saveDescription(\XenBulletins\BrandHub\Entity\Brand $brand)
        {
            $message = $this->plugin('XF:Editor')->fromInput('description');
            
            $descEntity = $this->finder('XenBulletins\BrandHub:BrandDescription')->where('brand_id', $brand->brand_id)->fetchOne();
            if(!$descEntity)
            {
                $descEntity = $this->em()->create('XenBulletins\BrandHub:BrandDescription');
            }
            
            $descEntity->description = $message;
            $descEntity->brand_id = $brand->brand_id;
            $descEntity->save();
            
            return $descEntity;
        }
        
        public function actionSave(ParameterBag $params)
	{   
            $this->assertPostOnly();

            if ($params->brand_id)
            {
                $brand = $this->assertBrandExists($params->brand_id);
            }
            else
            {
                $brand = $this->em()->create('XenBulletins\BrandHub:Brand');
            }
            
            $descEntity = $this->saveDescription($brand);

            return $this->redirect($this->buildLink('bh_brands/brand', $brand));
	}
        
        
        protected function assertBrandExists($id, $with = null, $phraseKey = null)
        {
                return $this->assertRecordExists('XenBulletins\BrandHub:Brand', $id, $with, $phraseKey);
        }
        
//*********************************************************************************


}