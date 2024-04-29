<?php

namespace BS\ScheduledPosting\XF\Service\ProfilePost;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleCreate;

class Creator extends XFCP_Creator
{
    use ScheduleCreate;
}

if (false)
{
    class XFCP_Creator extends \XF\Service\ProfilePost\Creator {}
}