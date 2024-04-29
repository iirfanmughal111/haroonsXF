<?php
namespace XenBulletin\VideoByRon\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class ByRons extends AbstractController{
    
    public function actionIndex(ParameterBag $params)
    {

        $ronVideos = $this->finder('XenBulletin\VideoByRon:ByRons')->order('date', 'desc')->fetch();
  
        $viewParams = [

            'ronVideos' => $ronVideos,
            'featureVideo' => $ronVideos->first()
        ];
         
        return $this->view('XenBulletinByRons\VideoByRon:ByRons', 'xb_video_by_ron', $viewParams);  
       
    }
    
}
