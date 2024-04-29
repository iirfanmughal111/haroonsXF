<?php

namespace FS\Limitations\XF\Entity;

class ConversationMessage extends XFCP_ConversationMessage
{
    protected function _postSave()
    {
        // ---------- update user's conversation_message_count record ----------
        if($this->isInsert())
        {
                $user = $this->User;

                $data =[
                    'conversation_message_count' => $user->conversation_message_count + 1,
                ];

               $user->fastUpdate($data);
        }
        //----------------------------------------------------------------  
        
        return parent::_postSave();
    }
}
