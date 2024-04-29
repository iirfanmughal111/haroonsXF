<?php

namespace XenBulletins\StopHumanSpam\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread {

    public $moderator = false;

    public function actionIndex(ParameterBag $params) {
    
   // var_dump($params);exit;
   
        $parent = parent::actionIndex($params);
        if ($parent instanceof \XF\Mvc\Reply\View) {

            $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());
            $pending = \XF::finder('XF:Post')->where('thread_id', $thread->thread_id)->where('user_id', \XF::visitor()->user_id)->where('message_state', 'moderated')->fetch()->count();

            $pending = ($pending) ? True : False;
            $parent->setParam('pendingApproval', $pending);
        }
        return $parent;
    }

    protected function setupThreadReply(\XF\Entity\Thread $thread) {
        $message = $this->plugin('XF:Editor')->fromInput('message');
        $options = \XF::options();
        if (!\XF::visitor()->hasPermission('general', 'canBypassStopBannedWords')) {
            $stopHumanSpamModel = $this->repository("XenBulletins\StopHumanSpam:StopHumanSpam");
            if ($options->shsBannedPostContent && ($bWord = $stopHumanSpamModel->contentHasBannedWords($message, $options->shsBannedPostContentWords))) {

                $this->moderator = true;

//                throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }
        }

        return parent::setupThreadReply($thread);
    }

    protected function setupThreadEdit(\XF\Entity\Thread $thread) {

        $title = $this->filter('title', 'str');

        $options = \XF::options();
        if (!\XF::visitor()->hasPermission('general', 'canBypassStopBannedWords')) {

            $stopHumanSpamModel = $this->repository("XenBulletins\StopHumanSpam:StopHumanSpam");
            if ($options->shsBannedThreadTitle && ($bWord = $stopHumanSpamModel->contentHasBannedWords($title, $options->shsBannedThreadTitleWords))) {
                $this->moderator = true;

                // throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }
        }

        $editor = parent::setupThreadEdit($thread);

        if ($this->moderator) {
            $editor->setDiscussionState('moderated');
        }

        return $editor;
    }

    protected function finalizeThreadReply(\XF\Service\Thread\Replier $replier) {
        parent::finalizeThreadReply($replier);
        if ($this->moderator) {
            $post = $replier->getPost();
            $post->message_state = 'moderated';
            $post->save();
        }
    }

      protected function assertViewableThread($threadId, array $extraWith = [])
	{
        		/** @var \XF\Entity\Thread $thread */
		$thread = $this->em()->find('XF:Thread', $threadId, $extraWith);
     
                if(!\xf::visitor()->user_id  && $thread->discussion_state == 'moderated')
                {
                    throw $this->exception($this->notFound(\XF::phrase('content_submitted_displayed_pending_approval')));
        
                }
            return parent::assertViewableThread($threadId, $extraWith);
	}
}
