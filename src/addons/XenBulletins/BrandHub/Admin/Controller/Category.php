<?php

namespace XenBulletins\BrandHub\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XenBulletins\BrandHub\Entity;
use XF\Mvc\ParameterBag;


class Category extends AbstractController
{

        
       public function actionIndex()
       {
            $page = $this->filterPage();
            $perPage = 20;

            $brandCategories = $this->Finder('XenBulletins\BrandHub:Category');
            
            $total = $brandCategories->total();
            $this->assertValidPage($page, $perPage, $total, 'bh_category');
            $brandCategories->limitByPage($page, $perPage);
            
            
            

            $viewParams = [

                    'brandCategories' => $brandCategories->order('category_id', 'DESC')->fetch(),
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('XenBulletins\BrandHub:Category', 'bh_categories', $viewParams);
       }


//************************Add, Edit Function**********************************************

	   	public function categoryAddEdit($brandCategory)
		{
                    $viewParams = [
                        'brandCategory' => $brandCategory,

                    ];

                    return $this->view('XenBulletins\BrandHub:Category', 'bh_category_add_edit', $viewParams);
		}

		public function actionEdit(ParameterBag $params)
		{   
                    $brandCategory = $this->assertCategoryExists($params->category_id);

                    return $this->categoryAddEdit($brandCategory);
		}

		public function actionAdd()
		{
			$brandCategory = $this->em()->create('XenBulletins\BrandHub:Category');

			return $this->categoryAddEdit($brandCategory);
		}




//************************Save category**********************************************
	protected function tagSaveProcess(\XenBulletins\BrandHub\Entity\Category $brandCategory)
	{
		$form = $this->formAction();


		$input = $this->filter([
				'category_title' => 'STR',
			]);

                
                $brandCategoryName = $this->actionFind($input['category_title']);
               

		if (!$brandCategoryName) 
		{
			$form->basicEntitySave($brandCategory, $input);

			return $form;
		}
		else
		{
			if ($brandCategory->category_id == $brandCategoryName->category_id) 
			{
				$form->basicEntitySave($brandCategory, $input);

				return $form;
			}
			else
			{
				$phraseKey = $brandCategoryName->category_title." category already exists.";
		 		throw $this->exception(
					$this->notFound(\XF::phrase($phraseKey))
				);
			}
		}


	}

	public function actionSave(ParameterBag $params)
	{       
            $this->assertPostOnly();

            if ($params->category_id)
            {
                    $brandCategory = $this->assertCategoryExists($params->category_id);
            }
            else
            {
                $brandCategory = $this->em()->create('XenBulletins\BrandHub:Category');
            }

            $this->tagSaveProcess($brandCategory)->run();

            return $this->redirect($this->buildLink('bh_category'));
	}

//*********************************************************************************
        

        public function actionDelete(ParameterBag $params)
        {

                 $brandCategory = $this->assertCategoryExists($params->category_id);

                /** @var \XF\ControllerPlugin\Delete $plugin */
                $plugin = $this->plugin('XF:Delete');

                return $plugin->actionDelete(
                         $brandCategory,
                        $this->buildLink('bh_category/delete',  $brandCategory),
                        $this->buildLink('bh_category/edit',  $brandCategory),
                        $this->buildLink('bh_category'),
                         $brandCategory->category_title
                );
        }


        protected function assertCategoryExists($id, $with = null, $phraseKey = null)
        {
                return $this->assertRecordExists('XenBulletins\BrandHub:Category', $id, $with, $phraseKey);
        }


        public function actionFind($title)
        {

                $brandCategoryName = $this->finder('XenBulletins\BrandHub:Category')->where('category_title',$title)->fetchOne();
                return $brandCategoryName;

        }

}