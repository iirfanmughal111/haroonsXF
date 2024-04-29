<?php

namespace HoU\Attachment\XF\Entity;

use XF\Entity\User;

class Post extends XFCP_Post
{
    /**
     * @param User $user
     * @return bool
     */
    public function _hasLikePost(User $user) {
		foreach ($this->Reactions as $reaction) {
			if($reaction->reaction_user_id === $user->user_id) {
				return true;
			}
		}

        return false;
    }

}