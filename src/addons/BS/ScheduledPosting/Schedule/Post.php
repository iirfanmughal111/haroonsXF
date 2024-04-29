<?php

namespace BS\ScheduledPosting\Schedule;

use BS\ScheduledPosting\Entity\Schedule;
use BS\ScheduledPosting\Schedule\Concerns\StandartPost;
use XF\Mvc\Entity\Entity;

class Post extends AbstractHandler
{
    use StandartPost;

    /**
     * @param \BS\ScheduledPosting\Entity\Schedule $schedule
     * @param \XF\Mvc\Entity\Entity|\XF\Entity\Post $post
     * @return bool
     */
    protected function _post(Schedule $schedule, Entity $post)
    {
        $save = $this->_savePost($schedule, $post);

        if ($save) {
            $this->sendNotifications($post);
            $this->getThreadRepo()->rebuildThreadPostPositions($post->thread_id);
        }

        return $save;
    }

    /**
     * @param \XF\Entity\Post $post
     */
    protected function sendNotifications($post)
    {
        /** @var \XF\Service\Post\Preparer $preparer */
        $preparer = \XF::service('XF:Post\Preparer', $post);
        $preparer->setMessage($post->message);

        /** @var \XF\Service\Post\Notifier $notifier */
        $notifier = \XF::service('XF:Post\Notifier', $post, 'reply');
        $notifier->setMentionedUserIds($preparer->getMentionedUserIds()); // todo: fix mentions
        $notifier->setQuotedUserIds($preparer->getQuotedUserIds());
        $notifier->notifyAndEnqueue(3);
    }

    public function getEntityWith()
    {
        return ['Thread'];
    }

    /**
     * @return \XF\Mvc\Entity\Repository|\XF\Repository\Thread
     */
    protected function getThreadRepo()
    {
        return \XF::repository('XF:Thread');
    }
}