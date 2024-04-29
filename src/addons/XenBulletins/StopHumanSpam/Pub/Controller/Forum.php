<?php

namespace XenBulletins\StopHumanSpam\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum {

    public $moderator = false;

    protected function setupThreadCreate(\XF\Entity\Forum $forum) {

        $title = $this->filter('title', 'str');
        $message = $this->plugin('XF:Editor')->fromInput('message');

        $options = \XF::options();

        if (!\XF::visitor()->hasPermission('general', 'canBypassStopBannedWords')) {

            $stopHumanSpamModel = $this->repository("XenBulletins\StopHumanSpam:StopHumanSpam");

            if ($options->shsBannedThreadTitle && ($bWord = $stopHumanSpamModel->contentHasBannedWords($title, $options->shsBannedThreadTitleWords))) {
                $this->moderator = true;

                // throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }

            if ($options->shsBannedPostContent && ($bWord = $stopHumanSpamModel->contentHasBannedWords($message, $options->shsBannedPostContentWords))) {
                $this->moderator = true;
                //   throw $this->exception($this->notFound(\XF::phrase('shs_thread_title_contains_banned_words', array('bWord' => $bWord))));
            }
        }
        $creator = parent::setupThreadCreate($forum);

        if ($this->moderator) {
            $creator->setDiscussionState('moderated');
        }

        return $creator;
    }

    public function actionForum(ParameterBag $params) {
        $parent = parent::actionForum($params);
        if ($parent instanceof \XF\Mvc\Reply\View) {

            $forum = $this->assertViewableForum($params->node_id ?: $params->node_name, $this->getForumViewExtraWith());
            $pending = \XF::finder('XF:Thread')->where('node_id', $forum->node_id)->where('user_id', \XF::visitor()->user_id)->where('discussion_state', 'moderated')->fetch()->count();

            $pending = ($pending) ? True : False;
            $parent->setParam('pendingApproval', $pending);
        }

        $visitor = \xf::visitor();

        if (!$visitor->hasPermission('forum','approveUnapprove')) {


            $threads = $parent->getParam('threads');

            foreach ($threads as $key => $thread) {

                if ($thread->discussion_state == 'moderated') {

                    unset($threads[$key]);
                }
            }
            $parent->setParam('threads', $threads);
        }
        return $parent;
    }
      public function actionPostThread(ParameterBag $params) {

        $parent = parent::actionPostThread($params);

        if ($this->isPost()) {

            $switches = $this->filter([
                'inline-mode' => 'bool'
      
            ]);

            if ($switches['inline-mode']) {

                $forum = $this->assertViewableForum($params->node_id ?: $params->node_name, ['DraftThreads|' . \XF::visitor()->user_id]);
                return $this->redirect($this->buildLink('forums', $forum, ['pending_approval' => 1]));
            }
        }

        return $parent;
    }

}
