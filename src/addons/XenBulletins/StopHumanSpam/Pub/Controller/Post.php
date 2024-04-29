<?php

namespace XenBulletins\StopHumanSpam\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post {

    public $moderator = false;

    protected function setupPostEdit(\XF\Entity\Post $post) {
        $message = $this->plugin('XF:Editor')->fromInput('message');
        $title = $this->filter('title', 'str');
        $options = \XF::options();
        if (!\XF::visitor()->hasPermission('general', 'canBypassStopBannedWords')) {

            $stopHumanSpamModel = $this->repository("XenBulletins\StopHumanSpam:StopHumanSpam");
            if ($options->shsBannedThreadTitle && ($bWord = $stopHumanSpamModel->contentHasBannedWords($title, $options->shsBannedThreadTitleWords))) {
                $this->moderator = true;
                // throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }

            if ($options->shsBannedPostContent && ($bWord = $stopHumanSpamModel->contentHasBannedWords($message, $options->shsBannedPostContentWords))) {
                $this->moderator = true;
                //throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }
        }

        return parent::setupPostEdit($post);
    }

    protected function finalizePostEdit(\XF\Service\Post\Editor $editor, \XF\Service\Thread\Editor $threadEditor = null) {
        if ($this->moderator) {
            $post = $editor->getPost();
            if ($post->isFirstPost()) {
                $thread = $post->Thread;
                $thread->discussion_state = 'moderated';
                $thread->save();
            } else {
                $post->message_state = 'moderated';
                $post->save();
            }
        }
        parent::finalizePostEdit($editor, $threadEditor);
    }

}
