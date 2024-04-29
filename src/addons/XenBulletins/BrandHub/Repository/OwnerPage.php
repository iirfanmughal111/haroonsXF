<?php

namespace XenBulletins\BrandHub\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Repository;
use XF\Util\Arr;
use XF\Entity\Attachment;


class OwnerPage extends Repository {

      
    	public function getMediaTypeFromAttachment(\XF\Entity\Attachment $attachment)
	{
		$data = $attachment->Data;
		if (!$data)
		{
			throw new \InvalidArgumentException("Attachment entity '$attachment->attachment_id' doesn't contain the expected Data relation.");
		}

		$extension = $data->getExtension();

		return $this->getMediaTypeFromExtension($extension);
	}

	public function getMediaTypeFromExtension($extension)
	{
		$options = $this->options();
		
		$imageExtensions = Arr::stringToArray($options->bh_ImageExtensions);
//		$videoExtensions = Arr::stringToArray($options->xfmgVideoExtensions);
//		$audioExtensions = Arr::stringToArray($options->xfmgAudioExtensions);

		if (in_array($extension, $imageExtensions))
		{
			return 'image';
		}
		else if (in_array($extension, $videoExtensions))
		{
			return 'video';
		}
		else if (in_array($extension, $audioExtensions))
		{
			return 'audio';
		}
		else
		{
			return false;
		}
	}
        
        
        
       /*************************flimstrip****************/
        
        public function getCurrentPositionInAlbum(Attachment $Item_Attachment, $content_id, array $limits = [])
	{
        

		$finder = $this->findMediaForAlbum($content_id, $limits);
               
		return $finder->total();
	}
        
        public function findMediaForAlbum($content_id, array $limits = [])
	{
		$finder = $this->findMediaForList($limits);
              
		$finder->where('content_id',$content_id)->where('content_type','bh_ownerpage');

		return $finder;
	}

        
        public function findMediaForList(array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true
		], $limits);


		$finder = $this->finder('XF:Attachment');


                $finder->order('attach_date','DESC');

	

		return $finder;
	}
        
        
        
}