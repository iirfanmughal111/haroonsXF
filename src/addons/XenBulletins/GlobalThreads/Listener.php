<?php

namespace XenBulletins\GlobalThreads;

class Listener {

    public static function preRender(\XF\Template\Templater $templater, &$type, &$template, array &$params) {
        if ($template == "forum_view" or $template == "thread_view") {
            if (isset($params['forum'])) {
                $forum = $params['forum'];
                $globalThreads = self::getGlobalThreads($forum);
                if ($globalThreads) {
                    $threadFinder = \XF::finder('XF:Thread')->where('thread_id', $globalThreads);
                    $globalThreads = $threadFinder->order($threadFinder->expression('FIELD(%s, '. implode(',', $globalThreads).')', 'thread_id'))->fetch();
                }
                $params['global_threads'] = $globalThreads;
            }
        }
    }

    public static function getGlobalThreads($forum) {
        $nodeBreadCrumbs = $forum->Node->breadcrumb_data;

        $nodeIds = [];
        $globalThreads = [];
        foreach ($nodeBreadCrumbs as $node) {
            $nodeIds[] = $node['node_id'];
        }
       

        $nodes = \XF::finder('XF:Node')->where('node_id', $nodeIds)->fetch();
            
        foreach ($nodes AS $id => $data) {
            if ($data->g_thread_ids) {
                $globalThreads = array_merge($globalThreads, $data->g_thread_ids);
            }
        }
                             
        if ($forum->Node->g_thread_ids) {
            $globalThreads = array_merge($globalThreads, $forum->Node->g_thread_ids);
        }

        return $globalThreads;
    }

}
