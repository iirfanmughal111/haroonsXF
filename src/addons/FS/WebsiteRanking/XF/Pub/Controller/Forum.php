<?php

namespace FS\WebsiteRanking\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use FS\WebsiteRanking\Helper;

class Forum extends XFCP_Forum
{
        protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
        {
                $forum = $creator->getForum();
                 
                if($forum && Helper::isApplicableForum($forum))
                {
                    $thread = $creator->getThread();
                    $thread->fastUpdate('is_complain',1);

                    //stil thread is in moderated state so don't need calculate these stats.
                    
                    // Recalculate issue percentage of this node when new issue create
                    if ($thread->discussion_state == 'visible') Helper::calculateIssuePercentageOfNode($forum);  
                }
                
                
                return parent::finalizeThreadCreate($creator);
        } 
}
