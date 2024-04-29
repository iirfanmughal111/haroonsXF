<?php

namespace FS\WebsiteRanking\XF\Repository;

use XF\Mvc\ParameterBag;

class Node extends XFCP_Node
{
    
//    public function getFullNodeList(\XF\Entity\Node $withinNode = null, $with = null)
//    {
//        $parentNodeId = intval(\xf::app()->options()->fs_web_ranking_parent_web_id);
//
//        if (!$parentNodeId) {
//            return parent::getFullNodeList($withinNode, $with);
//        }
//
//        /** @var \XF\Finder\Node $finder */
//        $finder = $this->finder('XF:Node')->order('lft');
//        if ($withinNode) {
//            $finder->descendantOf($withinNode);
//        }
//        if ($with) {
//            $finder->with($with);
//        }
//
//
//        return $finder->where('node_id', '!=', $parentNodeId)->fetch();
//    }
//
//    public function findNodesForList(\XF\Entity\Node $withinNode = null)
//    {
//        $finder = parent::findNodesForList($withinNode);
//
//        $parentNodeId = intval(\xf::app()->options()->fs_web_ranking_parent_web_id);
//
//        if ($parentNodeId) {
//            return $finder->where('node_id', '!=', $parentNodeId);
//        }
//
//        return $finder;
//    }
    
    
    //==========================================
    
    
    	public function getFullWebsiteNodeList(\XF\Entity\Node $withinNode = null, $with = null)
	{
                $parentNodeId = intval(\xf::app()->options()->fs_web_ranking_parent_web_id);
        
		/** @var \XF\Finder\Node $finder */
		$finder = $this->finder('XF:Node')->whereOr(['parent_node_id',$parentNodeId],['node_id',$parentNodeId])->setDefaultOrder('lft');
		if ($withinNode)
		{
			$finder->descendantOf($withinNode);
		}
		if ($with)
		{
			$finder->with($with);
		}
                
                return $finder;
	}
}
