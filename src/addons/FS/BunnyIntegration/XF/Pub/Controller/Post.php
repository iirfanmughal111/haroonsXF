<?php

namespace FS\BunnyIntegration\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
    public function actionEdit(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);

        if ($this->isPost() && $post->isFirstPost()) {
            $visitor = \XF::visitor();
            $app = \XF::app();

            $jopParams = [
                'threadId' => $post->Thread->thread_id,
            ];

            $jobID = $visitor->user_id . '_bunnyVideo_' . time();

            $app->jobManager()->enqueueUnique($jobID, 'FS\BunnyIntegration:BunnyUpload', $jopParams, false);
        }

        return parent::actionEdit($params);
    }

    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
    {
        $thread = $creator->getThread();
        $visitor = \XF::visitor();
        $app = \XF::app();

        $jopParams = [
            'threadId' => $thread->thread_id,
        ];

        $jobID = $visitor->user_id . '_bunnyVideo_' . time();

        $app->jobManager()->enqueueUnique($jobID, 'FS\BunnyIntegration:BunnyUpload', $jopParams, false);
        //    $app->jobManager()->runUnique($jobID, 120);

        return parent::finalizeThreadCreate($creator);
    }
}
