<?php

namespace FS\MediaTagSetting\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;


class MediaTag extends AbstractController
{
  
       public function actionIndex()
       {
            $page = $this->filterPage();
            $perPage = 20;

            $mediaTags = $this->Finder('FS\MediaTagSetting:MediaTag');
            
            $total = $mediaTags->total();
            $this->assertValidPage($page, $perPage, $total, 'media-tag');
            $mediaTags->limitByPage($page, $perPage);
            

            $viewParams = [

                    'mediaTags' => $mediaTags->order('id', 'DESC')->fetch(),
                
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total
            ];

            return $this->view('FS\MediaTagSetting:MediaTag', 'fs_media_tag_list', $viewParams);
       }

//************************ Add Edit **********************************************

        public function addEdit($mediaTag)
        {
            $mediaSites = $this->getMediaSites();
            $userGroups = $this->getUserGroups();
            
            
            $viewParams = [
                'mediaTag' => $mediaTag,
                'mediaSites' => $mediaSites,
                'userGroups' => $userGroups
            ];

            return $this->view('FS\MediaTagSetting:MediaTag', 'fs_media_tag_add_edit', $viewParams);
        }

        public function actionEdit(ParameterBag $params)
        {   
            $mediaTag = $this->assertMediaTagExists($params->id);

            return $this->addEdit($mediaTag);
        }

        public function actionAdd()
        {
                $mediaTag = $this->em()->create('FS\MediaTagSetting:MediaTag');

                return $this->addEdit($mediaTag);
        }


//************************ Save Process **********************************************
                

	protected function mediaTagSaveProcess(\FS\MediaTagSetting\Entity\MediaTag $mediaTag)
	{
            $form = $this->formAction();

            $input = $this->filter([

                            'media_site_ids' => 'array',
                            'user_group_ids' => 'array-int',
                            'custom_message' => 'STR'
                    ]);


            //add white spaces to fixing issue in LIKE query
            $input['title'] = ' '.implode(' , ', $input['media_site_ids']).' ';



            // to validate that media site is not allready assign to other 
            $mediaTagRecord = null;
            $mediaName = null;

            $arrayDiff = array_diff($input['media_site_ids'], ($mediaTag->media_site_ids?: []));
            foreach ($arrayDiff as $mediaSiteId)
            {
                $mediaTagRecord = $this->actionFind($mediaSiteId);

                if($mediaTagRecord)
                {
                    $mediaName = $mediaSiteId;
                    break;
                }
            }


            if (!$mediaTagRecord) 
            {
                $form->basicEntitySave($mediaTag, $input);
                return $form;
            }
            else
            {
                if ($mediaTag->id == $mediaTagRecord->id) 
                {
                    $form->basicEntitySave($mediaTag, $input);
                    return $form;
                }
                else
                {
                    $phraseKey = $mediaName." MediaSite already assigned to another setting.";
                    throw $this->exception($this->notFound($phraseKey));
                }
            }    
        }

	public function actionSave(ParameterBag $params)
	{       
            $this->assertPostOnly();

            if ($params->id)
            {
                $mediaTag = $this->assertMediaTagExists($params->id);
            }
            else
            {
                $mediaTag = $this->em()->create('FS\MediaTagSetting:MediaTag');
            }
            
            $this->mediaTagSaveProcess($mediaTag)->run();

            return $this->redirect($this->buildLink('media-tag'));
	}

//************************* Delete ********************************************
        

        public function actionDelete(ParameterBag $params)
        {

                 $mediaTag = $this->assertMediaTagExists($params->id);
                 
                /** @var \XF\ControllerPlugin\Delete $plugin */
                $plugin = $this->plugin('XF:Delete');

                return $plugin->actionDelete(
                        $mediaTag,
                        $this->buildLink('media-tag/delete',  $mediaTag),
                        $this->buildLink('media-tag/edit',  $mediaTag),
                        $this->buildLink('media-tag'),
                        $mediaTag->title
                );
        }
 //*********************************************************************     


        protected function assertMediaTagExists($id, $with = null, $phraseKey = null)
        {
                return $this->assertRecordExists('FS\MediaTagSetting:MediaTag', $id, $with, $phraseKey);
        }


        public function actionFind($mediaSiteId)
        {           
            $finder = $this->finder('FS\MediaTagSetting:MediaTag');
            $mediaTagRecord = $finder->where('title','LIKE',$finder->escapeLike($mediaSiteId, '% ? %'))->fetchOne();
                   
            return $mediaTagRecord;
        }
        
        protected function getMediaSites()
	{
            $bbCodeMediaSiteRepo = $this->repository('XF:BbCodeMediaSite');
            $mediaSites = $bbCodeMediaSiteRepo->findBbCodeMediaSitesForList()->fetch();

            return $mediaSites;
	}
         
        protected function getUserGroups()
	{
            return $this->repository('XF:UserGroup')->findUserGroupsForList()->fetch();
	}

}