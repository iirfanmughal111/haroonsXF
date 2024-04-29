<?php

namespace FS\CheckAttachment\Pub\Controller;

class Thread extends XFCP_Thread
{
    /**
     * @param \XF\Entity\Thread $thread
     *
     * @return \XF\Service\Thread\Replier
     */
    protected function setupThreadReply(\XF\Entity\Thread $thread)
    {
        $options = \XF::options();
        $applicable_forum = $options->ca_applicable_forum;
        if (in_array($thread->node_id, $applicable_forum)) {

        $message = $this->plugin('XF:Editor')->fromInput('message');

        $pattern = "/ATTACH|IMG|MEDIA/";
        $exist = preg_match_all($pattern, $message, $match);

        $attachmentFinder = $this->finder('XF:Attachment')
            ->where('temp_hash', $this->filter('attachment_hash', 'str'))->fetchOne();

        if ($exist == '' && $attachmentFinder == NULL) {
            throw $this->exception($this->error(\XF::phrase('fs_attachment_required')));
        }
    }
        return parent::setupThreadReply($thread);
    }
}
