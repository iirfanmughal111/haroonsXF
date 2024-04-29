<?php

namespace FS\ApprovePostThreads\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{
    public function actionApprove(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id);
        if (!$post->canApproveUnapprove($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            /** @var \XF\Service\Post\Approver $approver */
            $approver = \XF::service('XF:Post\Approver', $post);
            $approver->approve();

            return $this->redirect($this->buildLink('threads', $post));
        } else {
            $viewParams = [
                'post' => $post,
                'forum' => $post->Thread->Forum
            ];
            return $this->view('XF:Post\Approve', 'post_approve', $viewParams);
        }
    }
}
