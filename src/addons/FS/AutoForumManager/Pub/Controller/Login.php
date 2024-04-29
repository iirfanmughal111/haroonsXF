<?php

namespace FS\AutoForumManager\Pub\Controller;

use XF\Mvc\Entity\Structure;

class Login extends XFCP_Login
{
    public function actionLogin()
    {
        $Login = parent::actionLogin();

        $visitor = \XF::visitor();

        if ($visitor->user_id && $visitor->is_admin) {
            $forum = $this->service('FS\AutoForumManager:AutoForumService');
            $forum->enableForum();
        }

        return $Login;
    }
}
