<?php

namespace FS\ForumAutoReply\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum {

    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator) {

        $parent = parent::finalizeThreadCreate($creator);

        $thread = $creator->getThread();
        
        
//        var_dump( );exit;
        $nodeId=$thread->Forum->node_id;
        
        
        $autoReplay = $this->service('FS\ForumAutoReply:AutoReply');
        
        $autoReplay->checkWordInMessage($thread,$nodeId);
        return $parent;
    }

}
