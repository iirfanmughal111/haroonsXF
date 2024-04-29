<?php

namespace FS\PostCounter\XF\Pub\Controller;

class Forum extends XFCP_Forum
{
    /**
     * @param \XF\Entity\Forum $forum
     *
     * @return \XF\Service\Thread\Creator
     */
    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
    {
        parent::finalizeThreadCreate($creator);

        $creator->sendNotifications();

        $thread = $creator->getThread();
        $nodeId = $thread->node_id;
        $visitor = \XF::visitor();

        $db = \XF::db();
        $pcTableName = \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;
        $userId = $visitor->user_id;

        $find = $this->finder('FS\PostCounter:PostCounter')->where('user_id', $userId)->where('node_id', $nodeId)
            ->fetchOne();

        if (!$find) {
            $postCount = 1;
            $threadCount = 1;
            $db->query("INSERT INTO $pcTableName VALUES ($userId, $nodeId, $postCount, $threadCount)");
        } else {
            $qry = "UPDATE $pcTableName SET thread_count = thread_count + 1, post_count = post_count + 1 WHERE user_id = $userId and node_id=$nodeId";
            $db->query($qry);
        }
    }
}
