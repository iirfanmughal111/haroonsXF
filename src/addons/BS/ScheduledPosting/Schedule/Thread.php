<?php

namespace BS\ScheduledPosting\Schedule;

use BS\ScheduledPosting\Entity\Schedule;
use XF\Entity\User;
use XF\Mvc\Entity\Entity;

class Thread extends AbstractHandler
{
    protected function _schedule(Schedule $schedule, Entity $thread, $user)
    {
        if ($thread->isVisible()) {
            $thread->discussion_state = 'scheduled';
        }

        return true;
    }

    /**
     * @param \BS\ScheduledPosting\Entity\Schedule $schedule
     * @param \XF\Mvc\Entity\Entity|\XF\Entity\Thread $thread
     * @return bool
     * @throws \XF\PrintableException
     */
    protected function _post(Schedule $schedule, Entity $thread)
    {
        $thread->discussion_state = 'visible';
        $thread->post_date = $schedule->posting_date;

        /** @var \XF\Entity\Post $post */
        if ($post = $thread->FirstPost) {
            $post->post_date = $schedule->posting_date;

            if ($post->post_id === $thread->last_post_id) {
                $thread->last_post_date = $schedule->posting_date;
            }

            $save = $thread->save(false, false)
                && $post->save(false, false);

            if ($save) {
                $this->sendNotifications($post);
                $thread->rebuildCounters();
            }

            return $save;
        }

        return $thread->save(true, false);
    }

    protected function sendNotifications($post)
    {
        $notifier = \XF::service('XF:Post\Notifier', $post, 'thread');
        $notifier->notifyAndEnqueue(3);
    }
}