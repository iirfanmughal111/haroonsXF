<?php

namespace BS\ScheduledPosting\Repository;

use XF\Entity\User;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Repository;

class Schedule extends Repository
{
    protected $handlerCache = [];

    public function postScheduled()
    {
        $schedules = $this->finder('BS\ScheduledPosting:Schedule')
            ->readyForPosting()
            ->limit(50)
            ->fetch();

        foreach ($schedules as $schedule) {
            $this->post($schedule);
        }
    }

    /**
     * @param \BS\ScheduledPosting\Entity\Schedule $schedule
     * @throws \XF\PrintableException
     */
    public function post(\BS\ScheduledPosting\Entity\Schedule $schedule)
    {
        $handler = $this->getScheduleHandler($schedule->content_type);
        $content = $schedule->Content;

        if ($handler && $content) {
            $handler->post($schedule, $content);
        }
    }

    /**
     * @param Entity $content
     * @param User|null $user
     * @param $postingDate
     * @return boolean
     * @throws \XF\PrintableException
     */
    public function schedule(Entity $content, $user, $postingDate)
    {
        $handler = $this->getScheduleHandler($content->getEntityContentType());

        if ($handler) {
            return $handler->schedule($content, $user, $postingDate);
        }
    }

    /**
     * @param string $type
     * @param bool $throw
     *
     * @return \BS\ScheduledPosting\Schedule\AbstractHandler|null
     * @throws \Exception
     */
    public function getScheduleHandler($type, $throw = false)
    {
        if (isset($this->handlerCache[$type]))
        {
            return $this->handlerCache[$type];
        }

        $handlerClass = \XF::app()->getContentTypeFieldValue($type, 'schedule_handler_class');
        if (!$handlerClass)
        {
            if ($throw)
            {
                throw new \InvalidArgumentException("No schedule handler for '$type'");
            }
            return null;
        }

        if (!class_exists($handlerClass))
        {
            if ($throw)
            {
                throw new \InvalidArgumentException("Schedule handler for '$type' does not exist: $handlerClass");
            }
            return null;
        }

        $handlerClass = \XF::extendClass($handlerClass);
        $handler = new $handlerClass($type);

        $this->handlerCache[$type] = $handler;

        return $handler;
    }
}