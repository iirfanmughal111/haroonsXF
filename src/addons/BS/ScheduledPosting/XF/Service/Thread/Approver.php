<?php

namespace BS\ScheduledPosting\XF\Service\Thread;

class Approver extends XFCP_Approver
{
    protected function onApprove()
    {
        $thread = $this->thread;

        if ($thread->Schedule) {
            if ($thread->FirstPost) {
                if ($thread->Schedule->posting_date <= \XF::$time) {
                    return $this->getScheduleRepo()->post($thread->Schedule);
                }

                return $this->getScheduleRepo()->schedule($thread, $thread->User, $thread->Schedule->posting_date);
            }

            $thread->Schedule->delete();
        }

        return parent::onApprove();
    }

    protected function getScheduleRepo()
    {
        return $this->repository('BS\ScheduledPosting:Schedule');
    }
}