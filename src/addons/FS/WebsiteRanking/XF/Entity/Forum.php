<?php

namespace FS\WebsiteRanking\XF\Entity;

use XF\Mvc\Entity\Structure;
use XF\Entity\Thread;
use FS\WebsiteRanking\Helper;

class Forum extends XFCP_Forum
{
    public function canView(&$error = null)
    {
        if (Helper::isApplicableForum($this) && !\xf::visitor()->hasPermission('fs_website_ranking', 'check')) {
            return false;
        }

        return parent::canView($error);
    }

    public function getNewContentState(\XF\Entity\Thread $thread = null)
    {
        if (!$thread) {
            if ($this->Node->parent_node_id == \xf::app()->options()->fs_web_ranking_parent_web_id) {
                return 'moderated';
            }
        }

        return  parent::getNewContentState($thread);
    }


    public function threadAdded(Thread $thread)
    {
        $parent = parent::threadAdded($thread);

        if (Helper::isApplicableForum($thread->Forum)) {
            Helper::calculateIssuePercentageOfNode($thread->Forum);  // Recalculate issues percentage of this node (website) when issue discussion_state update
        }

        return $parent;
    }


    public function threadRemoved(Thread $thread)
    {
        $parent = parent::threadRemoved($thread);

        if (Helper::isApplicableForum($thread->Forum)) {
            Helper::calculateIssuePercentageOfNode($thread->Forum);  // Recalculate issues percentage of this node (website) when issue discussion_state update
        }

        return $parent;
    }
}
