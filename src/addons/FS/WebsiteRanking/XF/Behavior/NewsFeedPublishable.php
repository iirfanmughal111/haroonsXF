<?php

namespace FS\WebsiteRanking\XF\Behavior;

use FS\WebsiteRanking\Helper;

class NewsFeedPublishable extends XFCP_NewsFeedPublishable
{
        public function postSave()
	{   
            if($this->entity instanceof \XF\Entity\Post) $forum = $this->entity->Thread->Forum;
            if($this->entity instanceof \XF\Entity\Thread) $forum = $this->entity->Forum;

            
            if($forum && Helper::isApplicableForum($forum)); // if node is the websiteRanking then don't save NewsFeeds
            else return parent::postSave();
	}
    
}