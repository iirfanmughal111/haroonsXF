<?php

namespace BS\ScheduledPosting\XF\Service\Thread;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleCreate;

class Replier extends XFCP_Replier
{
    use ScheduleCreate;
}