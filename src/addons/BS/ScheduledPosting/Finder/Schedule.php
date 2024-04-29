<?php


namespace BS\ScheduledPosting\Finder;


use XF\Mvc\Entity\Finder;

class Schedule extends Finder
{
    public function readyForPosting()
    {
        $this->where('posting_date', '<=', \XF::$time + 30);

        return $this;
    }
}