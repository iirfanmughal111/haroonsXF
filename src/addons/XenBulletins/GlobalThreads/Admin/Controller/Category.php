<?php

namespace XenBulletins\GlobalThreads\Admin\Controller;

use XF\Mvc\FormAction;

class Category extends XFCP_Category {

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data) {

        $threadIds = $this->filter('g_thread_ids', 'str');

        if ($threadIds) {

            $threadIds = array_unique(explode(',', $threadIds));
            $node->set('g_thread_ids', $threadIds);
        } else {
            $node->set('g_thread_ids', []);
        }
        parent::saveTypeData($form, $node, $data);
    }

}
