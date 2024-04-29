<?php

namespace FS\AutoForumManager\Service;

class AutoForumService extends \XF\Service\AbstractService
{

    public function enableForum()
    {
        $visitor = \XF::visitor();

        $finder = $this->finder('FS\AutoForumManager:AutoForumManager')->where('admin_id', $visitor->user_id)->fetch();

        foreach ($finder as $value) {
            $findNode = $this->finder('XF:Node')->where('node_id', $value->node_id)->where('display_in_list', 0)->fetchOne();
            if ($findNode) {
                $findNode->fastUpdate('display_in_list', 1);

                if ($findNode->parent_node_id != 0) {
                    $getParent = \XF::finder('XF:Node')->whereId($findNode->parent_node_id)->where('display_in_list', 0)->fetchOne();
                    if ($getParent) {
                        $getParent->fastUpdate('display_in_list', 1);
                    }
                }
            }
        }
    }
}
