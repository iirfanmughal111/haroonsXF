<?php


namespace BS\ScheduledPosting\XF\Service\Concerns;

trait Scheduled
{
    protected $schedulePostingDate = null;

    public function setScheduled($postingDate)
    {
        $this->schedulePostingDate = $postingDate;
    }

    protected function _validate()
    {
        $errors = parent::_validate();

        if (empty($errors) && $this->schedulePostingDate !== null) {
            if ($this->schedulePostingDate < time() + 60 && $this->schedulePostingDate !== 0) {
                $errors[] = \XF::phrase('bssp_minimum_scheduled_posting_time_is_1_minute');
            }
        }

        return $errors;
    }

    /**
     * @return \BS\ScheduledPosting\Repository\Schedule
     */
    protected function getScheduleRepo()
    {
        return $this->repository('BS\ScheduledPosting:Schedule');
    }
}