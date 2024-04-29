<?php

namespace BS\ScheduledPosting\XF\Service\ProfilePost;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleEdit;

class Editor extends XFCP_Editor
{
    use ScheduleEdit;
}

if (false)
{
    class XFCP_Editor extends \XF\Service\ProfilePost\Editor {}
}