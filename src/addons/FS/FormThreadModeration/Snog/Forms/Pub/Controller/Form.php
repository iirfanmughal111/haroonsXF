<?php

namespace FS\FormThreadModeration\Snog\Forms\Pub\Controller;


class Form extends XFCP_Form
{
    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator, $params = [])
    {
        $threadId = $this->filter('thread_id', 'uint');
        if ($threadId) {
            $thread = $creator->getThread();
            $thread->fastUpdate('parent_thread_id', $threadId);
        }

        return parent::finalizeThreadCreate($creator, $params);
    }
}
