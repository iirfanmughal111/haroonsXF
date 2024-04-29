<?php

namespace FS\CloseThread\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
    /**
     * @param \XF\Entity\Thread $thread
     *
     * @return \XF\Service\Thread\Replier
     */
    public function actionCloseThread(ParameterBag $params)
    {
        $this->assertPostOnly();

        $thread = $this->assertViewableThread($params->thread_id);

        $editor = $this->getEditorService($thread);

        if ($thread->discussion_open) {
            $editor->setDiscussionOpen(false);
            $text = \XF::phrase('fs_unclose_thread');
        } else {
            $editor->setDiscussionOpen(true);
            $text = \XF::phrase('fs_close_thread');
        }

        if (!$editor->validate($errors)) {
            return $this->error($errors);
        }

        $editor->save();

        $reply = $this->redirect($this->getDynamicRedirect());
        $reply->setJsonParams([
            'text' => $text,
            'discussion_open' => $thread->discussion_open
        ]);

        $options = \XF::options();
        $applicable_id = $options->close_thread_prefix_id;

        $prefix = $this->Finder('XF:ThreadPrefix')->whereId($applicable_id)->fetchOne();

        if ($prefix) {
            $thread->fastUpdate('prefix_id', $applicable_id);
        }

        return $reply;
    }
}
