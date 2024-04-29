<?php

namespace BS\ScheduledPosting\XF\Service\ProfilePost;

use XF\Entity\ProfilePost;

class Approver extends XFCP_Approver
{
    protected function onApprove()
    {
        $profilePost = $this->profilePost;

        if ($profilePost->Schedule)
        {
            if ($profilePost->Schedule->posting_date <= \XF::$time)
            {
                return $this->getScheduleRepo()->post($profilePost->Schedule);
            }

            return $this->getScheduleRepo()->schedule($profilePost, $profilePost->User, $profilePost->Schedule->posting_date);
        }

        return parent::onApprove();
    }

    protected function getScheduleRepo()
    {
        return $this->repository('BS\ScheduledPosting:Schedule');
    }
}

if (false)
{
    class XFCP_Approver extends \XF\Service\ProfilePost\Approver {}
}