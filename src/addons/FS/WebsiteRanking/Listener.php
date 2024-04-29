<?php

namespace FS\WebsiteRanking;

use XF\Mvc\Entity\Entity;
use XF\Template\Templater;
use FS\WebsiteRanking\Helper;

class Listener
{   
    
    public static function templatePreRender_forum_Post_Thread(Templater $templater, &$type, &$template, array &$params)
    {
        $forum = isset($params['forum'])?$params['forum']: null;
        
        
        if($forum && Helper::isApplicableForum($forum))
        {
            $template = 'fs_wr_forum_post_issue';
        }
        
    }
    
    
    
    public static function templatePreRender_thread_edit(Templater $templater, &$type, &$template, array &$params)
    {
        $forum = isset($params['forum'])?$params['forum']: null;
        
        
        if($forum && Helper::isApplicableForum($forum))
        {
            $template = 'fs_wr_issue_edit';
        }
    }
    
   

}