<?php

namespace BS\ScheduledPosting\XF\Entity;

use XF\Entity\Post;

/**
 * RELATIONS
 * @property \BS\ScheduledPosting\Entity\Schedule|null Schedule
 */

class Thread extends XFCP_Thread
{
    public function canView(&$error = null)
    {
        $canView = parent::canView($error);

        if ($canView) {
            if ($this->Schedule && ! $this->canEditSchedule()) {
                $canView = false;
            }
        }

        return $canView;
    }

    public function canReply(&$error = null)
    {
        return parent::canReply($error) && ! $this->Schedule;
    }

    public function canEditSchedule()
    {
        if (! $this->Schedule) {
            return false;
        }

        $visitor = \XF::visitor();

        if ($this->Schedule->content_user_id == $visitor->user_id) {
            return true;
        }

        return $visitor->hasNodePermission($this->node_id, 'manageAnyThread');
    }

    protected function threadHidden($hardDelete = false)
    {
        if ($this->isStateChanged('discussion_state', 'scheduled') == 'enter') {
            return;
        }

        parent::threadHidden($hardDelete);
    }

    public function postRemoved(Post $post)
    {
        if ($post->isStateChanged('message_state', 'scheduled') == 'enter'
            && $post->post_id == $this->first_post_id
        ) {
            return;
        }

        parent::postRemoved($post);
    }
}
