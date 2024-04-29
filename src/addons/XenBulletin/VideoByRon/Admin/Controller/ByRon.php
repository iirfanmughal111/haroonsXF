<?php

namespace XenBulletin\VideoByRon\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XenBulletin\VideoByRon\Entity\ByRons;
use XF\Mvc\ParameterBag;


class ByRon extends AbstractController
{

    public function actionIndex()
    {
        $page = $this->filterPage();
        $perPage = 20;

        $rons = $this->Finder('XenBulletin\VideoByRon:ByRons')->order('ron_id', 'desc');

        $total = $rons->total();
        $this->assertValidPage($page, $perPage, $total, 'by-rons');
        $rons->limitByPage($page, $perPage);


        $viewParams = [

            'rons' => $rons->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,

            'ronLogo' => $this->app()->fs()->has('data://brand_img/logo_images/ron-logo.jpg')
        ];

        return $this->view('XenBulletin\VideoByRon:ByRon', 'xb_videos_by_ron', $viewParams);
    }


    //************************Add, Edit Function**********************************************

    public function addEdit($ron)
    {
        $viewParams = [
            'ron' => $ron
        ];

        return $this->view('XenBulletin\VideoByRon:ByRon\Edit', 'ron_addEdit', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $ron = $this->assertRonExists($params->ron_id);
        return $this->addEdit($ron);
    }

    public function actionAdd()
    {
        $ron = $this->em()->create('XenBulletin\VideoByRon:ByRons');

        return $this->addEdit($ron);
    }





    //************************ Save Ron**********************************************

    protected function saveProcess(ByRons $ron)
    {
        $this->checkValidation();

        $form = $this->formAction();

        $input = $this->getFormData();

        //                $videoId = $this->getVideoIdFromUrl($input['upload_type'], $input['video_url']);
        //                $input['video_id'] = $videoId;

        if (strpos($input['video_url'], 'youtube') === false) {
            $handlerClass = "XenBulletins\VideoPages:$handlerClass";
        }

        $form->basicEntitySave($ron, $input);

        return $form;
    }


    protected function checkValidation()
    {
        $videoUrl = $this->filter('video_url', 'str');
        $urlParsedArr = parse_url($videoUrl);

        if ($urlParsedArr['host'] != "www.youtube.com") {
            throw $this->exception($this->error(\XF::phrase('Invalid Video URL Please enter youtube video link')));
        }
    }

    public function getFormData()
    {
        return  $this->filter([
            'title' => 'str',
            'video_url' => 'str'
        ]);
    }

    //        public function getVideoIdFromUrl($uploadType,$videoUrl)
    //        {
    //            $videoId = '';
    //            
    //            if($uploadType == 'facebook')
    //            {       
    //            
    //                $mediaSiteIds = ['facebook'];
    //
    //                $bbCodeMediaSiteRepo = $this->repository('XF:BbCodeMediaSite');
    //                $mediaSites = $bbCodeMediaSiteRepo->findActiveMediaSites()->whereIds($mediaSiteIds)->fetch();
    //
    //                $mediaSite = $bbCodeMediaSiteRepo->urlMatchesMediaSiteList($videoUrl, $mediaSites);
    //                
    //                if($mediaSite)
    //                {
    //                    $videoId = $mediaSite['media_id'];
    //                }
    //                else
    //                {
    //                    throw $this->exception($this->error(\XF::phrase('Facebook video url format invalid')));
    //                }
    //
    //            }
    //            
    //            return $videoId;
    //        }


    //        public function getUploadFile()
    //        {
    //            $uploadType = $this->filter('upload_type','str');
    //            
    //            if($uploadType == 'advert')
    //            {
    //                $upload = $this->request->getFile('advert_img', false, false);
    //            }
    //            else
    //            {
    //                $upload = $this->request->getFile('fb_img', false, false);
    //            }
    //            
    //            return $upload;
    //        }

    public function finalizeRon(ByRons $ron, $videoId)
    {
        $videoUrl = "http://www.youtube.com/watch?v=" . $videoId;

        $ron->video_url = $videoUrl;
        $ron->video_id = $videoId;
        $ron->save();
    }

    public function thumbnailSaveProcess(ByRons $ron)
    {
        $videoUrl = $this->filter('video_url', 'str');



        $mediaSiteIds = ['youtube'];

        $bbCodeMediaSiteRepo = $this->repository('XF:BbCodeMediaSite');
        $mediaSites = $bbCodeMediaSiteRepo->findActiveMediaSites()->whereIds($mediaSiteIds)->fetch();

        $mediaSite = $bbCodeMediaSiteRepo->urlMatchesMediaSiteList($videoUrl, $mediaSites);

        $embedDataHandler = $this->createEmbedDataHandler($mediaSite['media_site_id']);
        $tempFile = $embedDataHandler->getTempThumbnailPath($videoUrl, $mediaSite['media_site_id'], $mediaSite['media_id']);


        $thumbnailPath = $ron->getAbstractedCustomImgPath();

        $thumbnailGenerator = $this->service('XenBulletin\VideoByRon:Media\ThumbnailGenerator');
        $thumbnailGenerator->getTempThumbnailFromImage($tempFile, $thumbnailPath);

        $this->finalizeRon($ron, $mediaSite['media_id']);
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params->ron_id) {
            $ron = $this->assertRonExists($params->ron_id);
        } else {
            $ron = $this->em()->create('XenBulletin\VideoByRon:ByRons');
        }

        $this->saveProcess($ron)->run();

        $this->thumbnailSaveProcess($ron);

        return $this->redirect($this->buildLink('by-rons'));
    }



    //*******************Delete Function**************************************************
    public function actionDelete(ParameterBag $params)
    {

        $ron = $this->assertRonExists($params->ron_id);

        if ($this->isPost()) {
            $fs = $this->app()->fs();

            $type = 'jpg';
            $absImgPath = $ron->getAbstractedCustomImgPath($type);


            //                        if(!$fs->has($absImgPath))
            //                        {
            //                            $type = 'gif';
            //                            $absImgPath = $ron->getAbstractedCustomImgPath($type);
            //                        }

            if ($fs->has($absImgPath)) {
                $fs->delete($absImgPath);
            }
        }


        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        return $plugin->actionDelete(
            $ron,
            $this->buildLink('by-rons/delete', $ron),
            $this->buildLink('by-rons/edit', $ron),
            $this->buildLink('by-rons'),
            $ron->title
        );
    }



    //********************************    *****************************************

    public function actionUploadLogo()
    {
        if ($this->isPost()) {
            $upload = $this->request->getFile('logo_img', false, false);

            $uploadService = $this->service('XenBulletin\VideoByRon:Upload');

            if ($upload && !$uploadService->setImageFromUpload($upload)) {
                return $this->error($uploadService->getError());
            }

            if ($upload && !$uploadService->updateImage()) {
                return $this->error(\XF::phrase('new_image_could_not_be_processed'));
            }

            return $this->redirect($this->buildLink('by-rons'));
        }

        $params = [
            'ronLogo' => $this->app()->fs()->has('data://brand_img/logo_images/ron-logo.jpg')
        ];

        return $this->view('', 'upload_logo', $params);
    }






    // ******************************************************

    protected function assertRonExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('XenBulletin\VideoByRon:ByRons', $id, $with, $phraseKey);
    }



    public function createEmbedDataHandler($bbCodeMediaSiteId)
    {
        $handlers = $this->getEmbedDataHandlers();

        if (isset($handlers[$bbCodeMediaSiteId])) {
            $handlerClass = $handlers[$bbCodeMediaSiteId];
        } else {
            $handlerClass = 'XenBulletin\VideoByRon\EmbedData\BaseData';
        }

        if (strpos($handlerClass, ':') === false && strpos($handlerClass, '\\') === false) {
            $handlerClass = "XenBulletin\VideoByRon:$handlerClass";
        }


        $handlerClass = \XF::stringToClass($handlerClass, '\%s\EmbedData\%s');

        $handlerClass = \XF::extendClass($handlerClass);

        return new $handlerClass($this->app());
    }

    protected function getEmbedDataHandlers()
    {
        $handlers = [
            'youtube' => 'XenBulletin\VideoByRon:YouTube'
        ];

        return $handlers;
    }
}
