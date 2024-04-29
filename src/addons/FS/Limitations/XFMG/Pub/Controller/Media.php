<?php

namespace FS\Limitations\XFMG\Pub\Controller;

use XF\Mvc\ParameterBag;

class Media extends XFCP_Media
{
//    public function actionAdd()
//    {
//        $visitor = \XF::visitor();
//        if (!$visitor->canAddMedia())
//        {
//                return $this->noPermission();
//        }
//        
//        $this->checkMediaStorageLimits();
//        
//        return parent::actionAdd();
//    }
//    
//    
//    
//    protected function checkMediaStorageLimits()
//    {
//        $visitor = \XF::visitor();
//        
//        $visitorMediaQuota = $visitor->xfmg_media_quota;
//        $mediaStorageLimit = $visitor->hasPermission('xfmgStorage', 'maxAllowedStorage');
//
//        // -------------------------- Check Media Storage  Limits --------------------------
//
//        if($mediaStorageLimit == 0)
//                throw $this->exception($this->notFound(\XF::phrase('fs_l_media_upload_not_allowed_please_upgrade')));
//        
//        $mediaStorageLimit = $mediaStorageLimit * 1024 *1024;   // convert MB into Byets
//        
//        if ( $visitorMediaQuota >= $mediaStorageLimit)
//        {   
//            $params = [
//                'visitorMediaQuota' => $visitorMediaQuota,
//                'mediaStorageLimit'   => $mediaStorageLimit
//            ];
//
//            throw $this->exception($this->notFound(\XF::phrase('fs_l_media_storage_limit_reached_please_upgrade',$params)));
//        }
//    }
    
}
