<?php

namespace BS\ScheduledPosting\XF\Service\Concerns;

trait ScheduleEdit
{
    use Scheduled;

    protected function _save()
    {
        $entity = parent::_save();

        if ($this->schedulePostingDate !== null)
        {
            if ($this->schedulePostingDate <= \XF::$time || $this->schedulePostingDate === 0)
            {
                $entity->Schedule->fastUpdate('posting_date', \XF::$time);
                $this->getScheduleRepo()->post($entity->Schedule);
            }
            else
            {
                $entity->Schedule->fastUpdate('posting_date', $this->schedulePostingDate);
            }
        }

        return $entity;
    }
}