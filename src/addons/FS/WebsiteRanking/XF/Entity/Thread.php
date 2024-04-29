<?php

namespace FS\WebsiteRanking\XF\Entity;

use XF\Mvc\Entity\Structure;
use FS\WebsiteRanking\Helper;

class Thread extends XFCP_Thread
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['issue_status'] =  ['type' => self::UINT, 'default' => 0];
        $structure->columns['is_complain'] =  ['type' => self::UINT, 'default' => 0];
        return $structure;
    }
    
    
    protected function _postDelete()
    {
        $parent = parent::_postDelete();
        
        if ($this->Forum->Node->parent_node_id == \xf::app()->options()->fs_web_ranking_parent_web_id) 
        {
            // Recalculate issue percentage of this node (website) when any issue delete
            if($this->discussion_state=='visible')
            {
                Helper::calculateIssuePercentageOfNode($this->Forum);  
            }
        }
        
        return $parent;
    }
    
	
}
