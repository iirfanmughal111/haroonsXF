<?php

namespace FS\BunnyIntegration\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    protected function _postDelete()
    {

        $app = \xf::app();

        $visitor = \XF::visitor();
        $app = \XF::app();

        $jopParams = [
            'threadId' => $this->thread_id,
        ];

        $jobID = $visitor->user_id . '_deleteBunnyVideo_' . time();

        $app->jobManager()->enqueueUnique($jobID, 'FS\BunnyIntegration:DeleteVideo', $jopParams, false);
        // $app->jobManager()->runUnique($jobID, 120);

        return parent::_postDelete();
    }
}
