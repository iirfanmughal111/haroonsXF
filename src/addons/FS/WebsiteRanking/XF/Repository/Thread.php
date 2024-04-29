<?php

namespace FS\WebsiteRanking\XF\Repository;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
    
//    public function findThreadsWithLatestPosts()
//    {
//        $finder = parent::findThreadsWithLatestPosts();
//
//        $parentNodeId = intval(\xf::app()->options()->fs_web_ranking_parent_web_id);
//
//        if ($parentNodeId) {
//
//            $nodeFinder = \xf::finder('XF:Node')->where("parent_node_id", $parentNodeId);
//            $tempNodeIds = $nodeFinder->pluckfrom('node_id')->fetch()->toArray();
//
//            $tempNodeIds[] = $parentNodeId;
//
//            return $finder->where('node_id', '!=', $tempNodeIds);
//        }
//
//        return $finder;
//    }
    
    
    
    	public function findThreadsForWatchedList($unreadOnly = false)
	{
		$visitor = \XF::visitor();
		$userId = $visitor->user_id;

		/** @var \XF\Finder\Thread $finder */
		$finder = $this->finder('XF:Thread');
		$finder
			->with('fullForum')
			->with('Watch|' . $userId, true)
			->where('discussion_state', 'visible')
                        ->where('is_complain', 0)               //   add this where condition so that complain threads don't display in watched list
			->setDefaultOrder('last_post_date', 'DESC');

		if ($unreadOnly)
		{
			$finder->unreadOnly($userId);
		}

		return $finder;
	}
    
}
