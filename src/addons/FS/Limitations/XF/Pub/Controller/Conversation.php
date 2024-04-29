<?php

namespace FS\Limitations\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Conversation extends XFCP_Conversation
{
    public function actionAdd()
    {
        $visitor = \XF::visitor();
        
        if (!$visitor->canStartConversation())
        {
                return $this->noPermission();
        }
        
        $this->checkConversationLimits();
        
        return parent::actionAdd();
    }
    
    
    public function actionAddReply(ParameterBag $params)
    {
        $this->checkConversationLimits();
        
        return parent::actionAddReply($params);
    }
    
    
    
    
    // protected function checkConversationLimits()
    // {
    //     $visitor = \XF::visitor();
        
    //     $visitorConversationMessageCount = $visitor->conversation_message_count;
    //     $conversasionLimit = $visitor->hasPermission('fs_limitations', 'fs_conversationLimit');
       
    //     // -------------------------- Check Conversasion  Limits --------------------------

    //     if ( ($conversasionLimit >= 0) && $visitorConversationMessageCount >= $conversasionLimit)
    //     {
            
    //         $accountType = $visitor->account_type;
            
    //         if($accountType == 1)
    //         {
    //             $upgradeUrl = $this->buildLink('account-upgrade/admirer');
    //         }
    //         elseif($accountType == 2) 
    //         {
    //             $upgradeUrl = $this->buildLink('account-upgrade/companion');
    //         }
    //         else
    //         {
    //             $upgradeUrl = $this->buildLink('account-upgrade');
    //         }
            
            
            
    //         if($conversasionLimit == 0)
    //             throw $this->exception($this->notFound(\XF::phrase('fs_l_conversation_not_allowed_please_upgrade',['upgradeUrl'=> $upgradeUrl])));
            
    //         $params = [
    //             'visitorConversationMessageCount' => $visitorConversationMessageCount,
    //             'conversasionLimit'   => $conversasionLimit,
    //             'upgradeUrl' => $this->buildLink('account-upgrade/')
    //         ];

    //         throw $this->exception($this->notFound(\XF::phrase('fs_l_conversation_limit_reached_please_upgrade',$params)));
            
            
            
    //     }
    // }
    
}
