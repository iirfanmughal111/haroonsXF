<?php

namespace BS\ScheduledPosting\XF\Service\Thread;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleCreate;

class Creator extends XFCP_Creator
{
    use ScheduleCreate;
}
