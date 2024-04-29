<?php

namespace BS\ScheduledPosting\Schedule;

use BS\ScheduledPosting\Entity\Schedule;
use XF\Mvc\Entity\Entity;
use XF\Entity\User;

abstract class AbstractHandler
{
    protected $contentType;

    /**
     * @param Schedule $schedule
     * @param Entity $content
     * @param User|null $user
     * @return boolean
     */
    protected abstract function _schedule(Schedule $schedule, Entity $content, $user);

    /**
     * @param Schedule $schedule
     * @param Entity $content
     * @return boolean
     */
    protected abstract function _post(Schedule $schedule, Entity $content);

    public function __construct($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @param Entity $content
     * @param User|null $user
     * @param integer|$postingDate
     * @return bool|Entity
     * @throws \XF\PrintableException
     */
    public function schedule(Entity $content, $user, $postingDate)
    {
        $scheduleAttributes = [
            'content_type' => $content->getEntityContentType(),
            'content_id' => $content->getEntityId(),
            'content_user_id' => $user ? $user->user_id : 0
        ];

        $em = \XF::em();
        $schedule = $em->findOne('BS\ScheduledPosting:Schedule', $scheduleAttributes);

        if (! $schedule) {
            $schedule = \XF::em()->create('BS\ScheduledPosting:Schedule');
            $schedule->bulkSet($scheduleAttributes);
        }

        $schedule->posting_date = $postingDate;

        if ($schedule->preSave() && $this->_schedule($schedule, $content, $user)) {
            return $schedule->save(false, false)
                && $content->save(false, false);
        }

        return false;
    }

    /**
     * @param Schedule $schedule
     * @param Entity|null $content
     * @return bool
     * @throws \XF\PrintableException
     */
    public function post(Schedule $schedule, Entity $content = null)
    {
        if (! $content) {
            $content = $schedule->Content;
            if (! $content) {
                return false;
            }
        }

        if ($this->_post($schedule, $content)) {
            $schedule->delete();
            $content->clearCache('Schedule');

            return true;
        }

        return false;
    }

    public function getEntityWith()
    {
        return [];
    }

    public function getContent($id)
    {
        return \XF::app()->findByContentType($this->contentType, $id, $this->getEntityWith());
    }

    public function getContentType()
    {
        return $this->contentType;
    }
}