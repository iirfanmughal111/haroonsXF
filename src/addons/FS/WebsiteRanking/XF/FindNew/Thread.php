<?php

namespace FS\WebsiteRanking\XF\FindNew;


class Thread extends XFCP_Thread
{
        protected function applyFilters(\XF\Finder\Thread $threadFinder, array $filters)
	{
            $threadFinder->where('is_complain', '=', 0); //  Don't add the threadIds of 'issue threads' in the result of findNew post (don't display issueThreads in new post)
            
            return parent::applyFilters($threadFinder,$filters);
        }
}