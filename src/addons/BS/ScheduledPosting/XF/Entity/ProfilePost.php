<?php

namespace BS\ScheduledPosting\XF\Entity;

/**
 * RELATIONS
 * @property \BS\ScheduledPosting\Entity\Schedule|null Schedule
 */

class ProfilePost extends XFCP_ProfilePost
{
    public function canView(&$error = null)
    {
        $canView = parent::canView($error);

        if ($canView)
        {
            if ($this->Schedule && !$this->canEditSchedule())
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

        return $visitor->hasPermission('profilePost', 'editAny');
    }

    protected function profilePostHidden($hardDelete = false)
    {
        if ($this->isStateChanged('message_state', 'scheduled') == 'enter')
        {
            return;
        }

        parent::profilePostHidden($hardDelete);
    }
}

if (false)
{
    class XFCP_ProfilePost extends \XF\Entity\ProfilePost {}
}