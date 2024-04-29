<?php

namespace BS\ScheduledPosting\Schedule\Concerns;

use BS\ScheduledPosting\Entity\Schedule;
use XF\Entity\Post;
use XF\Mvc\Entity\Entity;

trait StandartPost
{
    protected function _schedule(Schedule $schedule, Entity $post, $user)
    {
        if ($post->isVisible()) {
            $post->message_state = 'scheduled';
        }

        return true;
    }

    protected function _savePost(Schedule $schedule, Entity $post)
    {
        $post->message_state = 'visible';
        $post->post_date = $schedule->posting_date;

        return $post->save(true, false);
    }
}