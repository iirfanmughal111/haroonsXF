<?php

namespace BS\ScheduledPosting\XF\Finder;

use BS\ScheduledPosting\XF\Finder\Concerns\ScheduleCondition;
use XF\Mvc\Entity\Finder;

class Thread extends XFCP_Thread
{
    use ScheduleCondition;

    public function applyVisibilityChecksInForum(\XF\Entity\Forum $forum, $allowOwnPending = false)
    {
        parent::applyVisibilityChecksInForum($forum, $allowOwnPending);

        $this->replaceScheduleStates('discussion_state', $forum->node_id);

        return $this;
    }
}

if (false)
{
    class XFCP_Thread extends \XF\Finder\Thread {}
}