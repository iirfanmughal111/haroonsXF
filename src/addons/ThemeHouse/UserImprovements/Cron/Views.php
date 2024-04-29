<?php

namespace ThemeHouse\UserImprovements\Cron;

class Views
{
    public static function runViewUpdate()
    {
        $app = \XF::app();

        /** @var \ThemeHouse\userImprovements\Repository\User $userRepo */
        $userRepo = $app->repository('ThemeHouse\UserImprovements:User');
        $userRepo->batchUpdateProfileViews();
    }
}
