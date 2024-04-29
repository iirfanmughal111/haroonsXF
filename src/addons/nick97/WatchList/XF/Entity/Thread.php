<?php

namespace nick97\WatchList\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
    public function getWatchListExist()
    {
        $visitor = \XF::visitor();

        $recordWatchList = $this->finder('nick97\WatchList:WatchList')->where('user_id', $visitor->user_id)->where('thread_id', $this->thread_id)->fetchOne();

        if ($recordWatchList) {
            return true;
        } else {
            return false;
        }
    }
}
