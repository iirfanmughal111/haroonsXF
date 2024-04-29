<?php

namespace BS\ScheduledPosting\XF\Service\Post;

use XF\Entity\Post;

class Approver extends XFCP_Approver
{
    protected function onApprove()
    {
        $post = $this->post;

        if ($post->Schedule)
        {
            if ($post->Schedule->posting_date <= \XF::$time)
            {
                return $this->getScheduleRepo()->post($post->Schedule);
            }

            return $this->getScheduleRepo()->schedule($post, $post->User, $post->Schedule->posting_date);
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
    class XFCP_Approver extends \XF\Service\Post\Approver {}
}