<?php

namespace BS\ScheduledPosting\Cron;

class Schedule
{
    public static function posting()
    {
        \XF::repository('BS\ScheduledPosting:Schedule')->postScheduled();
    }
}