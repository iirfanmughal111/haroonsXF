<?php

namespace BS\ScheduledPosting\XF\Finder;

use BS\ScheduledPosting\XF\Finder\Concerns\ScheduleCondition;
use XF\Mvc\Entity\Finder;

class ProfilePost extends XFCP_ProfilePost
{
    use ScheduleCondition;

    public function applyVisibilityChecksForProfile(\XF\Entity\User $user, $allowOwnPending = true)
    {
        parent::applyVisibilityChecksForProfile($user, $allowOwnPending);

        $this->replaceScheduleStates('message_state');

        return $this;
    }
}

if (false)
{
    class XFCP_ProfilePost extends \XF\Finder\ProfilePost {}
}