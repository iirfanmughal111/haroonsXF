<?php

namespace BS\ScheduledPosting\XF\Finder;

use BS\ScheduledPosting\XF\Finder\Concerns\ScheduleCondition;
use XF\Mvc\Entity\Finder;

class Post extends XFCP_Post
{
    use ScheduleCondition;

    public function applyVisibilityChecksInThread(\XF\Entity\Thread $thread, $allowOwnPending = true)
    {
        parent::applyVisibilityChecksInThread($thread, $allowOwnPending);

        $this->replaceScheduleStates('message_state', $thread->node_id);

        return $this;
    }
}

if (false)
{
    class XFCP_Post extends \XF\Finder\Post {}
}