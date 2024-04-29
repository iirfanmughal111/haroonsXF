<?php

namespace BS\ScheduledPosting\XF\Pub\Controller;

use XF\Entity\User;
use XF\Entity\UserProfile;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    protected function setupProfilePostCreate(UserProfile $userProfile)
    {
        $creator = parent::setupProfilePostCreate($userProfile);

        if (\XF::visitor()->canCreateScheduled() && $this->filter('scheduled.is', 'bool'))
        {
            $creator->setScheduled($this->filter('scheduled.posting_date', 'datetime'));
        }

        return $creator;
    }
}

if (false)
{
    class XFCP_Member extends \XF\Pub\Controller\Member {}
}