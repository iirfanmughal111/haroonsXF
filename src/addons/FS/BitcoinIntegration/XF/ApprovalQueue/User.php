<?php

namespace FS\BitcoinIntegration\XF\ApprovalQueue;

class User extends XFCP_User
{
    public function actionApprove(\XF\Entity\User $user)
    {
        $parent = parent::actionApprove($user);

        // if ($user->account_type == 2) {
        //     $user->user_group_id = 5;
        //     $user->save();
        // }

        return $parent;
    }
}
