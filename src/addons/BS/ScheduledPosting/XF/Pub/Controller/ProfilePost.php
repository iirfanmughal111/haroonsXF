<?php

namespace BS\ScheduledPosting\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;
use XF\Mvc\RouteMatch;

class ProfilePost extends XFCP_ProfilePost
{
    protected function setupEdit(\XF\Mvc\Entity\Entity $profilePost)
    {
        $editor = parent::setupEdit($profilePost);

        if ($profilePost->canEditSchedule())
        {
            $editor->setScheduled($this->filter('scheduled.posting_date', 'datetime'));
        }

        return $editor;
    }

    public function actionEdit(ParameterBag $params)
    {
        $reply = parent::actionEdit($params);

        if ($reply instanceof View && $this->isPost())
        {
            $reply->setJsonParam('post_date', $reply->getParam('profilePost')->post_date);
        }

        return $reply;
    }
}

if (false)
{
    class XFCP_ProfilePost extends \XF\Pub\Controller\ProfilePost {}
}