<?php

namespace BS\ScheduledPosting\XF\Service\Post;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleEdit;

class Editor extends XFCP_Editor
{
    use ScheduleEdit;
}

if (false)
{
    class XFCP_Editor extends \XF\Service\Post\Editor {}
}