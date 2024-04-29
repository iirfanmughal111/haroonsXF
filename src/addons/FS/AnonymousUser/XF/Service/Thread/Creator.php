<?php

namespace FS\AnonymousUser\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    protected function setUser(\XF\Entity\User $user)
    {
        parent::setUser($user);

        if ($user->user_id == 0) {
            $this->thread->username = "anonymous";
            $this->post->username = "anonymous";
        }
    }
}
