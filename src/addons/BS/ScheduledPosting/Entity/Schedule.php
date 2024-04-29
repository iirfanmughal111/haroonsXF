<?php

namespace BS\ScheduledPosting\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null schedule_id
 * @property string content_type
 * @property int content_id
 * @property int content_user_id
 * @property int schedule_date
 * @property int posting_date
 *
 * GETTERS
 * @property mixed Content
 * @property \BS\ScheduledPosting\Schedule\AbstractHandler|null Handler
 *
 * RELATIONS
 * @property \XF\Entity\User User
 */
class Schedule extends Entity
{
    public function canView()
    {
        $handler = $this->Handler;

        return ($handler && $handler->canView($this));
    }

    public function getContent()
    {
        $handler = $this->Handler;
        return $handler ? $handler->getContent($this->content_id) : null;
    }

    /**
     * @return \BS\ScheduledPosting\Schedule\AbstractHandler|null
     * @throws \Exception
     */
    public function getHandler()
    {
        return $this->getScheduleRepo()->getScheduleHandler($this->content_type);
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_bs_schedule';
        $structure->shortName = 'BS\ScheduledPosting:Schedule';
        $structure->primaryKey = 'schedule_id';
        $structure->columns = [
            'schedule_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'content_type' => ['type' => self::STR, 'maxLength' => 25, 'required' => true],
            'content_id' => ['type' => self::UINT, 'required' => true],
            'content_user_id' => ['type' => self::UINT, 'required' => true],
            'schedule_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'posting_date' => ['type' => self::UINT, 'default' => 0]
        ];
        $structure->getters = [
            'Content' => true,
            'Handler' => true
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$content_user_id']
                ],
                'primary' => true
            ]
        ];

        return $structure;
    }

    /**
     * @return \BS\ScheduledPosting\Repository\Schedule
     */
    protected function getScheduleRepo()
    {
        return $this->repository('BS\ScheduledPosting:Schedule');
    }
}