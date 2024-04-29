<?php

namespace BS\ScheduledPosting\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

class Post extends XFCP_Post
{
    protected function setupPostEdit(\XF\Entity\Post $post)
    {
        $editor = parent::setupPostEdit($post);

        if ($post->canEditSchedule())
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
            $reply->setJsonParam('post_date', $reply->getParam('post')->post_date);
        }

        return $reply;
    }
}

if (false)
{
    class XFCP_Post extends \XF\Pub\Controller\Post {}
}