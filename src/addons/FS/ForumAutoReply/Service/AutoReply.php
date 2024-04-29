<?php

namespace FS\ForumAutoReply\Service;

class AutoReply extends \XF\Service\AbstractService {

    public function checkWordInMessage($thread, $nodeId) {


        $wordsForum = $this->getMatchWords($nodeId);

        $threadMessage = $thread->FirstPost->message;

        $noMatchMessage = $this->getNoMatchWords($nodeId);

        $match=0;
        
        if (count($wordsForum)) {

            foreach ($wordsForum as $record) {


                if ($record->word) {

                    $wordMatch = $this->contentHasBannedWords($threadMessage, $record->word);

                    if ($wordMatch) {

                        $randomUser = $this->getRandomUser($record->user_id);
                        $this->createPost($thread, $record->message, $randomUser);
                        $this->changePrefix($thread, $record->prefix_id);
                        $this->SecondaryGroup($thread, $record->user_group_id);
                         $this->closeThread($thread);

                        $match=1;
                        break;
                    }
                }
            }
        }

        if ($noMatchMessage && $match==0) {

            



            $randomUser = $this->getRandomUser($noMatchMessage->no_match_user_ids);
            $this->createPost($thread, $noMatchMessage->no_match_message, $randomUser);
            $this->changePrefix($thread, $noMatchMessage->no_match_prefix_id);
            $this->closeThread($thread);
        }
    }

    public function closeThread($thread) {

        $thread->fastUpdate('discussion_open', 0);
    }

    public function getRandomUser($userIds) {

        if ($userIds) {

            $userIds = explode(',', $userIds);

            $key = array_rand($userIds);

            return $this->finder('XF:User')->where('user_id', (int) $userIds[$key])->fetchOne();
        }
    }

    public function getNoMatchWords($nodeId) {


        return $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('prefix_id', '=', null)->where('no_match_prefix_id', '!=', null)->where('node_id', $nodeId)->fetchOne();
    }

    public function getMatchWords($nodeId) {


        return $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $nodeId)->fetch();
    }

    public function createPost($thread, $message, $user) {



        $replier = \XF::service('XF:Thread\Replier', $thread, $user);
        $replier->setIsAutomated();
        if (!$message) {
            $message = "--";
        }
        $replier->setMessage($message);

        $replier->save();

        $post = $replier->getPost();

        return $post;
    }

    public function changeGroup($thread, $userGroupId) {

        $user = $thread->User;
        $user->fastUpdate('user_group_id', $userGroupId);
    }

    public function SecondaryGroup($thread,$userGroupId){
        
        $user = $thread->User;
        
        if(!in_array($userGroupId,$user->secondary_group_ids)){
       
            $secodaryIds=$user->secondary_group_ids;
            
            array_push($secodaryIds,$userGroupId);
            
            $user->fastUpdate('secondary_group_ids', $secodaryIds);
        
        }
        
    }
    public function changePrefix($thread, $prefixId) {

        $editor = $this->service('XF:Thread\Editor', $thread);
        $editor->setPrefix($prefixId);
        $editor->save();
    }

    public function contentHasBannedWords($message, $bannedWords = '') {


        $message = 'ztstart ' . $message . ' ztend';

        if (!$message || !$bannedWords) {
            return false;
        }

        $bWords = array_map('trim', explode(",", $bannedWords));

        foreach ($bWords as $bWord) {
            if ($bWord) {
                if ((substr($bWord, 0, 1) == '*') && (substr($bWord, -1) == '*')) { //if it starts and ends with a *
                    $bWord = str_replace('*', '', $bWord);

                    preg_match("/(.*)" . preg_quote($bWord) . "(.*)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, 0, 1) == '*') { //if it starts with a *
                    $bWord = str_replace('*', '', $bWord);

                    preg_match("/(.*)" . preg_quote($bWord) . "(?!\pL)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, -1) == '*') { //if it ends with a *
                    $bWord = str_replace('*', '', $bWord);
                    preg_match("/(?<!\pL)" . preg_quote($bWord) . "(.*)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, 0, 1) != '*') {
                    preg_match("/(?<!\pL)" . preg_quote($bWord) . "(?!\pL)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }
            }
        }

        return false;
    }

}
