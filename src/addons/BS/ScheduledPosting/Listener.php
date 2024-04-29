<?php

namespace BS\ScheduledPosting;

use XF\Mvc\Entity\Entity;

class Listener
{
    public static function entityStructureThread(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->columns['discussion_state']['allowedValues'][] = 'scheduled';
        $structure->relations += [
            'Schedule' => [
                'entity' => 'BS\ScheduledPosting:Schedule',
                'type' => Entity::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'thread'],
                    ['content_id', '=', '$thread_id']
                ]
            ]
        ];
        $structure->defaultWith += ['Schedule'];
    }

    public static function entityStructurePost(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->columns['message_state']['allowedValues'][] = 'scheduled';
        $structure->relations += [
            'Schedule' => [
                'entity' => 'BS\ScheduledPosting:Schedule',
                'type' => Entity::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'post'],
                    ['content_id', '=', '$post_id']
                ]
            ]
        ];
        $structure->defaultWith += ['Schedule'];
    }

    public static function entityStructureProfilePost(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
        $structure->columns['message_state']['allowedValues'][] = 'scheduled';
        $structure->relations += [
            'Schedule' => [
                'entity' => 'BS\ScheduledPosting:Schedule',
                'type' => Entity::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'profile_post'],
                    ['content_id', '=', '$profile_post_id']
                ]
            ]
        ];
    }

    public static function entityPostDeleteClearSchedule(\XF\Mvc\Entity\Entity $entity)
    {
        if (isset($entity->structure()->relations['Schedule']) && $entity->Schedule)
        {
            $entity->Schedule->delete();
        }
    }
}