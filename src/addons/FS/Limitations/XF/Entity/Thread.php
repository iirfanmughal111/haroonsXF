<?php

namespace FS\Limitations\XF\Entity;

class Thread extends XFCP_Thread
{
    protected function _postSave()
    {
        // ---------- update user's daily_discussion_count record -------------
        if($this->isInsert())
        {
                $user = $this->User;

                $data =[
                    'daily_discussion_count' => $user->daily_discussion_count + 1,
                ];

               $user->fastUpdate($data);
        }
        
        //----------------------------------------------------------------  
        
        return parent::_postSave();
    }
}
