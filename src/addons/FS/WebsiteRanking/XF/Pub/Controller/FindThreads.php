<?php

namespace FS\WebsiteRanking\XF\Pub\Controller;


class FindThreads extends XFCP_FindThreads
{
        protected function getThreadResults(\XF\Finder\Thread $threadFinder, $pageSelected)
	{
            $threadFinder->where('is_complain', '=', 0); //  Don't add the threadIds of 'issue threads' in the result of findNew post (don't display issueThreads in new post)
            
            return parent::getThreadResults($threadFinder,$pageSelected);
        }
    
}
