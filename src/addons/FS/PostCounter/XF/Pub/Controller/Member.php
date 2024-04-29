<?php

namespace FS\PostCounter\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member {

    public function actionPostCounter(ParameterBag $params) {


        $options = \XF::options();
        $applicable_forum = $options->ca_applicable_forums;
        $user = $this->assertViewableUser($params->user_id);


        if (in_array('0', $applicable_forum) && empty($applicable_forum)) {
            
            $postCounters = null;
            
        } else {

            $postCounters = $this->finder('FS\PostCounter:PostCounter')->where('node_id', $applicable_forum)->where('user_id', $params->user_id)->fetch();
        }



        $viewParams = [
            'user' => $user,
            'postCounters' => $postCounters,
             
        ];

        return $this->view('FS\PostCounter:PostCounter', 'fs_post_counter_index', $viewParams);
    }

    /**
     * Returns true if there is at least one forum with a thread count
     * 
     * @param array $postCounter
     * @return boolean
     */
    protected function _containsThreadCount($postCounter) {
        foreach ($postCounter as $postCount) {
            if (array_key_exists('thread_count', $postCount)) {
                return true;
            }
        }

        return false;
    }

}
