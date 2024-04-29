<?php

namespace BS\ScheduledPosting\XF\Service\Thread;

use BS\ScheduledPosting\XF\Service\Concerns\ScheduleEdit;

class Editor extends XFCP_Editor
{
    use ScheduleEdit;
}