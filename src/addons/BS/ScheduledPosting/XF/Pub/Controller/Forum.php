<?php

namespace BS\ScheduledPosting\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
    {
        $creator = parent::setupThreadCreate($forum);

        if (\XF::visitor()->canCreateScheduled($forum->node_id) && $this->filter('scheduled.is', 'bool'))
        {
            $creator->setScheduled($this->filter('scheduled.posting_date', 'datetime'));
        }

        return $creator;
    }
}

if (false)
{
    class XFCP_Forum extends \XF\Pub\Controller\Forum {}
}