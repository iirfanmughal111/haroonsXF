<?php

namespace BS\ScheduledPosting\XF\Service\Concerns;

trait ScheduleCreate
{
    use Scheduled;

    protected function _save()
    {
        $entity = parent::_save();

        if ($this->schedulePostingDate !== null) {
            $this->getScheduleRepo()->schedule($entity, $this->user, $this->schedulePostingDate);
        }

        return $entity;
    }
}