<?php

namespace FS\WebsiteRanking\XF\Pub\Controller;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\ParameterBag;
use FS\WebsiteRanking\Helper;

class Thread extends XFCP_Thread
{
    public function actionSolved(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if (!($visitor->user_id || \XF::visitor()->is_admin)) {
            return $this->noPermission();
        }

        $thread = $this->assertViewableThread($params->thread_id);

//        if ($visitor->user_id != $thread->user_id) {
//            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
//        }

        if ($thread->Forum->Node->parent_node_id != \xf::app()->options()->fs_web_ranking_parent_web_id) {
            return $this->noPermission();
        }

        if ($this->isPost()) 
        {
            $thread->fastUpdate('issue_status', 1);
            
            if ($thread->discussion_state == 'visible')
            {
                Helper::calculateIssuePercentageOfNode($thread->Forum);  // Recalculate issues percentage of this node (website) when issue status update
            }

            return $this->redirect($this->getDynamicRedirect());
            
        } else {

            $viewParams = [
                'thread' => $thread,
            ];
            return $this->view('XF:Thread\Solved', 'fs_web_ranking_solved', $viewParams);
        }
    }
    
    
    
    public function actionUnsolved(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id || !\XF::visitor()->is_admin) 
        {
            return $this->noPermission();
        }

        $thread = $this->assertViewableThread($params->thread_id);

//        if ($visitor->user_id != $thread->user_id) {
//            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
//        }

        if ($thread->Forum->Node->parent_node_id != \xf::app()->options()->fs_web_ranking_parent_web_id) {
            return $this->noPermission();
        }

        if ($this->isPost()) 
        {
            $thread->fastUpdate('issue_status', 2);
            
            if ($thread->discussion_state == 'visible')
            {
                Helper::calculateIssuePercentageOfNode($thread->Forum);  // Recalculate issues percentage of this node (website) when issue status update
            }
                
            return $this->redirect($this->getDynamicRedirect());
        } 
        else 
        {
            $viewParams = [
                'thread' => $thread,
            ];
            
            return $this->view('XF:Thread\Unsolved', 'fs_wr_unsolved', $viewParams);
        }
    }
    
    
}
