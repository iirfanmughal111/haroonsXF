<?php

namespace BS\ScheduledPosting\XF\Entity;

class User extends XFCP_User
{
    public function canCreateScheduled($nodeId = null)
    {
        return $nodeId ? $this->hasNodePermission($nodeId, 'scheduledPosting') : $this->hasPermission('forum', 'scheduledPosting');
    }

    public function canCreateScheduledShowcaseItem($categoryId = null)
    {
        return $categoryId
            ? $this->hasShowcaseItemCategoryPermission($categoryId, 'scheduledPostingShowcase')
            : $this->hasPermission('xa_showcase', 'scheduledPostingShowcase');
    }

    public function canViewScheduled($nodeId = null)
    {
        return $nodeId ?
            $this->hasNodePermission($nodeId, 'viewScheduled') :
            $this->hasPermission('forum', 'viewScheduled');
    }
}
