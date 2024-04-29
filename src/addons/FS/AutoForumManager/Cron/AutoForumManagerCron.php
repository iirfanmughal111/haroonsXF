<?php

namespace FS\AutoForumManager\Cron;


class AutoForumManagerCron
{

    public static function disableForums()
    {
        $finder = \XF::finder('FS\AutoForumManager:AutoForumManager')->fetch();

        foreach ($finder as $value) {

            if ($value->User->last_activity <= time() - ($value->last_login_days * 86400)) {
                $forumData = $value->Node;
                $forumData->fastUpdate('display_in_list', 0);
                if ($forumData->parent_node_id != 0) {
                    $allData = \XF::finder('XF:Node')->where('parent_node_id', $forumData->parent_node_id)->where('display_in_list', 1)->fetchOne();
                    if (!$allData) {
                        $getParent = \XF::finder('XF:Node')->whereId($forumData->parent_node_id)->fetchOne();
                        $getParent->fastUpdate('display_in_list', 0);
                    }
                }
            }
        }
    }
}
