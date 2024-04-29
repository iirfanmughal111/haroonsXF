<?php

namespace BS\ScheduledPosting\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class Thread extends XFCP_Thread
{
    public function actionScheduleCancel(ParameterBag $params)
    {
        $thread = $this->assertViewableThread($params->thread_id);
        if (!$thread->canEditSchedule())
        {
            return $this->noPermission();
        }

        $plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
            $thread,
            $this->buildLink('threads/schedule-cancel', $thread),
            $this->buildLink('threads', $thread),
            $this->buildLink('forums', $thread->Forum),
            $thread->title,
            'bssp_schedule_cancel_confirm'
        );
    }

    protected function setupThreadReply(\XF\Entity\Thread $thread)
    {
        $replier = parent::setupThreadReply($thread);

        if (\XF::visitor()->canCreateScheduled($thread->node_id) && $this->filter('scheduled.is', 'bool'))
        {
            $replier->setScheduled($this->filter('scheduled.posting_date', 'datetime'));
        }

        return $replier;
    }

    protected function setupThreadEdit(\XF\Entity\Thread $thread)
    {
        $editor = parent::setupThreadEdit($thread);

        if ($thread->canEditSchedule())
        {
            $editor->setScheduled($this->filter('scheduled.posting_date', 'datetime'));
        }

        return $editor;
    }
}

if (false)
{
    class XFCP_Thread extends \XF\Pub\Controller\Thread {}
}