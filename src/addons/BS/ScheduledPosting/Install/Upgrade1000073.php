<?php

namespace BS\ScheduledPosting\Install;

trait Upgrade1000073
{
    protected function fixOldestScheduledThreads($position, array $stepData)
    {
        $perPage = 50;

        $db = \XF::db();
        $db->beginTransaction();

        if (! isset($stepData['max']))
        {
            $stepData['max'] = $db->fetchOne('
                SELECT MAX(post.post_id) 
                FROM xf_post as post
                LEFT JOIN xf_bs_schedule as schedule
                  ON (schedule.content_id = post.post_id and schedule.content_type = \'post\')
                WHERE message_state = \'scheduled\'
                  AND schedule.schedule_id IS NULL
            ');
        }

        $postIds = $db->fetchAllColumn('
            SELECT post.post_id as post_id
            FROM xf_post as post
            LEFT JOIN xf_bs_schedule as schedule
              ON (schedule.content_id = post.post_id and schedule.content_type = \'post\')
            WHERE message_state = \'scheduled\'
              AND schedule.schedule_id IS NULL
              AND post.post_id > ?
            LIMIT ?
        ', [$position, $perPage], 'post_id');

        $posts = $this->app->finder('XF:Post')
            ->where('post_id', $postIds)
            ->fetch();

        if (! $posts->count())
        {
            $db->commit();
            return true;
        }

        $next = $this->makePostsVisible($posts);

        $db->commit();

        return [
            $next,
            "{$next} / {$stepData['max']}",
            $stepData
        ];
    }

    protected function makePostsVisible($posts)
    {
        $next = 0;

        /** @var \XF\Entity\Post $post */
        foreach ($posts as $post)
        {
            $next = $post->post_id;

            $post->message_state = 'visible';
            $post->save();
        }

        return $next;
    }
}