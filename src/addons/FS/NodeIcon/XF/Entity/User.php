<?php

namespace FS\NodeIcon\XF\Entity;

use XF\Mvc\Entity\Entity;

class User extends XFCP_User
{
    public function canEditIcon(&$error = null)
    {
        $visitor = \XF::visitor();

        if ($visitor->hasPermission('forum', 'canEditIcon'))
        {
            return true;
        }

        return false;
    }
}