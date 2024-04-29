<?php

namespace FS\BunnyIntegration\Job;

use XF\Job\AbstractJob;

class DeleteVideo extends AbstractJob
{
    protected $defaultData = [];

    public function run($maxRunTime)
    {
        $s = microtime(true);
        $app = \xf::app();

        $threadId = $this->data['threadId'];

        $threads = $app->finder('FS\BunnyIntegration:DeleteBunnyVid')->where('thread_id', $threadId)->fetch();

        if (count($threads)) {
            foreach ($threads as $thread) {

                $bunnyService = \xf::app()->service('FS\BunnyIntegration\XF:BunnyServ');
                $bunnyService->delelteBunnyVideo($thread->bunny_library_id, $thread->bunny_video_id);
            }
        }
    }

    public function getStatusMessage()
    {
    }

    public function canCancel()
    {
        return true;
    }

    public function canTriggerByChoice()
    {
        return true;
    }
}
