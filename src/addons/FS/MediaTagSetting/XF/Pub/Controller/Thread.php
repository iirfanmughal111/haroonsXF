<?php

namespace FS\MediaTagSetting\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

class Thread extends XFCP_Thread
{
    public function actionIndex(ParameterBag $params)
    {
        $parent = parent::actionIndex($params);

        if ($parent instanceof \XF\Mvc\Reply\View) {
            $visitor = \XF::visitor();

            $posts = $parent->getParam('posts');

            $mediaTags = $this->Finder('FS\MediaTagSetting:MediaTag')->order('id', 'Desc')->fetch();

            $userGroupFinder =  \XF::finder('XF:UserGroup')->order('user_group_id', 'Asc');

            $userGroupIds = $userGroupFinder->fetchColumns('user_group_id');

            $userIds = array();
            foreach ($userGroupIds as $value) {
                array_push($userIds, strval($value['user_group_id']));
            }

            foreach ($mediaTags as $mediaTag) {

                $unselected = array_diff($userIds, $mediaTag->user_group_ids);

                if ($visitor->isMemberOf($unselected)) {
                    
                } else if ($visitor->isMemberOf($mediaTag->user_group_ids)) {
                    foreach ($posts as $post) {
                        $post->message = $this->removeMediaTags($post->message, $mediaTag);
                    }
                }
            }
        }

        return $parent;
    }


    public function removeMediaTags($message, \FS\MediaTagSetting\Entity\MediaTag $mediaTag)
    {
        $patterns = [];

        foreach ($mediaTag->media_site_ids as $mediaId) {
            $patterns[] = '#\[media=' . $mediaId . '([^\[]*?)\[/media\]#i';
        }

        return preg_replace($patterns, '[fs_custom_msg]' . $mediaTag->custom_message . '[/fs_custom_msg]', $message);
    }
}
