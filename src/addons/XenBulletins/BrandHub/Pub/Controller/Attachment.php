<?php


namespace XenBulletins\BrandHub\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;



class Attachment extends XFCP_Attachment{
    

    public function actionFull(ParameterBag $params)
	{
        
		$attachmentitem = $this->assertViewableAttachmentItem($params->attachment_id );

		$this->request->set('no_canonical', 1);

		return $this->rerouteController('XF:Attachment', 'index', ['attachment_id' => $params->attachment_id]);
	}
        
            protected function assertViewableAttachmentItem($attachmentId)
	{
    
	
		$attachmentitem = $this->em()->find('XF:Attachment', $attachmentId);

//		if (!$attachmentitem)
//		{
//			throw $this->exception($this->notFound(\XF::phrase('xfmg_requested_media_item_not_found')));
//		}
//		if (!$mediaItem->canView($error))
//		{
//			throw $this->exception($this->noPermission($error));
//		}

		return $attachmentitem;
	}

    
}
