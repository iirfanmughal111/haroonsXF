<?php

namespace BS\ScheduledPosting\Schedule;

use BS\ScheduledPosting\Entity\Schedule;
use BS\ScheduledPosting\Schedule\Concerns\StandartPost;
use XF\Mvc\Entity\Entity;

class ProfilePost extends AbstractHandler
{
    use StandartPost;

    protected function _post(Schedule $schedule, Entity $profilePost)
    {
        $save = $this->_savePost($schedule, $profilePost);

        if ($save)
        {
            $this->sendNotifications($profilePost);
        }

        return $save;
    }

    protected function sendNotifications($profilePost)
    {
        $notifier = \XF::service('XF:ProfilePost\Notifier', $profilePost);
        $notifier->notify();
    }
}