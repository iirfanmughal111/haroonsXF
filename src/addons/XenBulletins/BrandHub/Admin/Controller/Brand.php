<?php

namespace XenBulletins\BrandHub\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XenBulletins\BrandHub\Entity;
use XF\Mvc\ParameterBag;


class Brand extends AbstractController
{

        
       public function actionIndex()
       {
            $page = $this->filterPage();
            $perPage = 20;

            $brands = $this->Finder('XenBulletins\BrandHub:Brand');
            
            $total = $brands->total();
            $this->assertValidPage($page, $perPage, $total, 'bh_brand');
            $brands->limitByPage($page, $perPage);
            
            
            

            $viewParams = [

                    'brands' => $brands->order('brand_id', 'DESC')->fetch(),
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('XenBulletins\BrandHub:Brand', 'bh_brands', $viewParams);
       }



	   	public function brandAddEdit($brand)
		{

                    
                    $nodeTree = $this->getNodeTree();


                    $viewParams = [
                        'brand' => $brand,
                        'nodeTree' => $nodeTree,
                    ];

                    return $this->view('XenBulletins\BrandHub:Category', 'bh_brand_add_edit', $viewParams);
		}

		public function actionEdit(ParameterBag $params)
		{   
                    $brand = $this->assertBrandExists($params->brand_id,'Description');

                    return $this->brandAddEdit($brand);
		}

		public function actionAdd()
		{
			$brand = $this->em()->create('XenBulletins\BrandHub:Brand');

			return $this->brandAddEdit($brand);
		}




//************************Save category**********************************************
                
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
        
	protected function brandSaveProcess(\XenBulletins\BrandHub\Entity\Brand $brand)
	{
		$form = $this->formAction();
                
		$input = $this->filter([
				'brand_title' => 'STR',
                                'node_ids' => 'array',
                                'forums_link' => 'STR',
                                'website_link' => 'STR',
                                'for_sale_link' => 'STR',
                                'intro_link' => 'STR',
			]);
                
                
                
                $this->deleteBrandIdFromForums($brand);
                
                
                $nodeIds = $input['node_ids'];
                
                foreach ($nodeIds as $nodeId)
                {
                    
                    $node = $this->finder('XF:Forum')->where('node_id',$nodeId)->with('Node')->fetchOne();

                    if($node && $node->brand_id)
                    { 
               
                         $phraseKey = $node->Node->title." forum already assigned to another brand.";
		 		throw $this->exception(
					$this->notFound(\XF::phrase($phraseKey))
				);
                    }
                }
                
                 

                
                $brandName = $this->actionFind($input['brand_title']);
               

		if (!$brandName) 
		{
			$form->basicEntitySave($brand, $input);

			return $form;
		}
		else
		{
			if ($brand->brand_id == $brandName->brand_id) 
			{
				$form->basicEntitySave($brand, $input);

				return $form;
			}
			else
			{
				$phraseKey = $brandName->brand_title." brand already exists.";
		 		throw $this->exception(
					$this->notFound(\XF::phrase($phraseKey))
				);
			}
		}
                 


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
            
            $this->brandSaveProcess($brand)->run();
            
            $descEntity = $this->saveDescription($brand);
            
            $this->saveBrandIdInForums($brand);
            
            

            return $this->redirect($this->buildLink('bh_brand'));
	}

//*********************************************************************************
        

        public function actionDelete(ParameterBag $params)
        {

                 $brand = $this->assertBrandExists($params->brand_id);

                 if($this->isPost())
                 {               
                     
                     $description = $this->finder('XenBulletins\BrandHub:BrandDescription')->where('brand_id',$params->brand_id)->fetchOne();
                     $description->delete();
                     
                     $this->deleteBrandIdFromForums($brand);
                 }
                /** @var \XF\ControllerPlugin\Delete $plugin */
                $plugin = $this->plugin('XF:Delete');

                return $plugin->actionDelete(
                        $brand,
                        $this->buildLink('bh_brand/delete',  $brand),
                        $this->buildLink('bh_brand/edit',  $brand),
                        $this->buildLink('bh_brand'),
                        $brand->brand_title
                );
        }


        protected function assertBrandExists($id, $with = null, $phraseKey = null)
        {
                return $this->assertRecordExists('XenBulletins\BrandHub:Brand', $id, $with, $phraseKey);
        }


        public function actionFind($title)
        {

                $brandName = $this->finder('XenBulletins\BrandHub:Brand')->where('brand_title',$title)->fetchOne();
                return $brandName;

        }
        
        protected function getNodeTree()
	{
		$nodeRepo = \XF::repository('XF:Node');
		$nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());

		// only list nodes that are forums or contain forums
		$nodeTree = $nodeTree->filter(null, function($id, $node, $depth, $children, $tree)
		{
			return ($children || $node->node_type_id == 'Forum');
		});

		return $nodeTree;
	}
        
        
        public function saveBrandIdInForums($brand)
        {
            $nodeIds = $this->filter('node_ids', 'array');
            foreach ($nodeIds as $nodeId)
            {
                $node = $this->finder('XF:Forum')->where('node_id',$nodeId)->with('Node')->fetchOne();

                if($node && !$node->brand_id)
                {
                    $node->brand_id = $brand->brand_id;
                    $node->save();
                }
            }
        }
        
        
        public function deleteBrandIdFromForums($brand)
        {
            $nodes = $this->finder('XF:Forum')->where('brand_id',$brand->brand_id)->fetch();
            foreach ($nodes as $node)
            {
                $node->brand_id = 0;
                $node->save();
            }
        }

}