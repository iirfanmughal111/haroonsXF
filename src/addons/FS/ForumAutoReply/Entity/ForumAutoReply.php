<?php

namespace FS\ForumAutoReply\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class ForumAutoReply extends Entity
{

    
    public function getNoMatchUserIds(){
        
        return $this->getconverUserIds($this->no_match_user_ids);
        
    }
    
    public function getMatchUserids(){
        
       return $this->getconverUserIds($this->user_id);
        
    }
    public  function getconverUserIds($users)
    {
           
      $users_ids = explode(", ", $users);

        $users_names = array();

        foreach ($users_ids as $value) {
            $user = null;
            if ($value) {
                $user = $this->em()->findOne('XF:User', ['user_id' => $value]);

                if (!$user) {
                    throw $this->exception($this->error(\XF::phraseDeferred('requested_user_x_not_found', ['name' => $value])));
                }
                array_push($users_names, $user['username'] . ', ');
            }
        }

        $users_names = implode("", $users_names);

        return $users_names;
        $viewParams = [
            'no_match_user_names' => $users_names
        ];

        return $viewParams;
    }
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_forum_auto_reply';
        $structure->shortName = 'FS\ForumAutoReply:ForumAutoReply';
        $structure->contentType = 'fs_forum_auto_reply';
        $structure->primaryKey = 'message_id';
        $structure->columns = [
            'message_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'node_id' => ['type' => self::UINT],
            'word' => ['type' => self::STR, 'default' => null],
            'message' => ['type' => self::STR, 'default' => null],
            'user_id' => ['type' => self::STR, 'default' => null],
            'user_group_id' => ['type' => self::UINT, 'default' => null],
            'prefix_id' => ['type' => self::UINT, 'default' => null],

            'no_match_prefix_id' => ['type' => self::STR, 'default' => null],
            'no_match_message' => ['type' => self::STR, 'default' => null],
            'no_match_user_ids' => ['type' => self::STR, 'default' => null],

        ];

        $structure->relations = [
            'Node' => [
                'entity' => 'XF:Node',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
            ],

            'UserGroup' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => 'user_group_id',
            ],

            'Prefix' => [
                'entity' => 'XF:ThreadPrefix',
                'type' => self::TO_ONE,
                'conditions' => 'prefix_id',
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
    
  
}
