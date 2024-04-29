<?php

namespace XC\ThreadGPTBot\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class User extends XFCP_User {

    public function hasArticlePermission($permission) {
        

        return $this->hasPermission('bot_gpt_thread', $permission);
    }

}
