<?php

namespace BS\ScheduledPosting\XF\Entity;

/**
 * RELATIONS
 * @property \BS\ScheduledPosting\Entity\Schedule|null Schedule
 */

class Post extends XFCP_Post
{
    public function canView(&$error = null)
    {
        $canView = parent::canView($error);

        if ($canView && $this->Schedule)
        {
            if (!$this->canEditSchedule() && !\XF::visitor()->canViewScheduled($this->Thread->node_id))
            {
                $canView = false;
            }
        }

        return $canView;
    }

    public function canEditSchedule()
    {
        if (!$this->Schedule)
        {
            return false;
        }

        $visitor = \XF::visitor();

        if ($this->Schedule->content_user_id == $visitor->user_id)
        {
            return true;
        }

        return $visitor->hasNodePermission($this->Thread->node_id, 'manageAnyThread');
    }

    protected function postHidden($hardDelete = false)
    {
        if ($this->isStateChanged('message_state', 'scheduled') == 'enter')
        {
            return;
        }

        parent::postHidden($hardDelete);
    }
}

if (false)
{
    class XFCP_Post extends \XF\Entity\Post {}
}