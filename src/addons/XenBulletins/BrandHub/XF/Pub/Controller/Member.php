<?php


namespace XenBulletins\BrandHub\XF\Pub\Controller;
use XF\Mvc\ParameterBag;


class Member extends XFCP_Member {
       public function actionTabPages(ParameterBag $params) {

       
        $ownerPages = $this->finder('XenBulletins\BrandHub:OwnerPage')->where('user_id', $params->user_id)->fetch();

            $viewParams = [

                'ownerPages' => $ownerPages,
                ];

            return $this->view('XenBulletins\BrandHub:OwnerPage', 'bh_tab_owner_pages', $viewParams); 

    }
    
    
     public function actionItemListSub(ParameterBag $params){
        
        $subs = $this->finder('XenBulletins\BrandHub:ItemSub')->where('user_id', $params->user_id)->with('Item')->fetch();
        
        $user = $this->finder('XF:User')->where('user_id', $params->user_id)->fetchOne();

            $viewParams = [

                'sublist' => $subs,
                'user' => $user
                ];


            return $this->view('XenBulletins\BrandHub:OwnerPage', 'bh_tab_item_sub', $viewParams); 

        
    }
}
