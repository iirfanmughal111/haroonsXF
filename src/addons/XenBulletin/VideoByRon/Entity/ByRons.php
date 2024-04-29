<?php

namespace XenBulletin\VideoByRon\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class ByRons extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xb_videos_by_ron';
        $structure->shortName = 'XenBulletin\VideoByRon:ByRons';
        $structure->primaryKey = 'ron_id';
        $structure->columns = [
            
            'ron_id' => ['type' => self::UINT,'autoIncrement' => true],
            'title' => ['type' => self::STR, 'maxLength' => 255, 'required' => true],
            'video_url' => ['type' => self::STR, 'maxlength' => 500, 'default' => NULL],
            'video_id' => ['type' => self::STR, 'default' => ''],
            'date' => ['type' => self::UINT, 'default' => \XF::$time],
         
        ];

        return $structure;
    }

    public function getAbstractedCustomImgPath($type = 'jpg') 
    {
        $ronId = $this->ron_id;
        return sprintf('data://brand_img/ron_images/%d/%d.'.$type, floor($ronId / 1000), $ronId);
    }

    public function getImgUrl($canonical = true)
    {
        $ronId = $this->ron_id;
        $path = sprintf('brand_img/ron_images/%d/%d.jpg', floor($ronId / 1000), $ronId);
       
        
//        $absolutePath = \XF::getRootDirectory().'/data/'. $path;
//
//        if(!file_exists($absolutePath))
//        {
//            $path = sprintf('brand_img/a_images/%d/%d.gif', floor($advertId / 1000), $advertId);
//        }
        
        return \XF::app()->applyExternalDataUrl($path, $canonical);
    }
    
//    public function getVideoUrl() 
//    {
//        $uploadType = $this->upload_type;
//  
//        if($uploadType == 'facebook')
//        {
//            $videoId = $this->video_id;
//            return "https://www.facebook.com/v2.5/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fvideo.php%3Fv%3D$videoId";
//        }
//        else
//        {
//            return $this->video_url;
//        } 
//    }

}