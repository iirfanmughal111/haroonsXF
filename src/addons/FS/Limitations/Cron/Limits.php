<?php

namespace FS\Limitations\Cron;
//ini_set('max_execution_time', 0);

class Limits
{
    // Reset the Daily discussion Post limit of all Users. Every day midnight (set daily_discussion_count column to 0 for all users after 24 hours.)
    public static function resetDailyDiscussionLimit()
    {
        \XF::db()->query('Update xf_user set daily_discussion_count = 0');
    }

    public static function resetUserDailyAdsAndRepost()
    {
        \XF::db()->query('Update xf_user set daily_ads = 0, daily_repost = 0');
    }
}
