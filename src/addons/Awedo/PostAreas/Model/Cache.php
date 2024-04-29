<?php

/** 
  * @author Thomas Braunberger
  */

namespace Awedo\PostAreas\Model;

class Cache
{    
    public static function updatePostAreas ()
    {   
        $db = \XF::db();
        $paTableName = self::getPostAreasTableName();
               
        $class = \XF::extendClass('Awedo\PostAreas\Model\PostAndThreadStats');
        
        /* @var $stats \Awedo\PostAreas\Model\PostAndThreadStats */
        $stats = new $class;        
        
        // delete old data                
        $db->query("DELETE FROM $paTableName");
        
        // get highest user_id
        $lastUserId = $db->fetchOne('SELECT COUNT(*) FROM xf_user');
        
        // update post and thread counts
        for ($userId = 1; $userId <= $lastUserId; $userId++)
        {
            $allStats = $stats->getPostAndThreadCounts($userId);
            
            foreach ($allStats as $subForumStats)
            {
                $nodeId = $subForumStats['node_id'];
                $postCount = $subForumStats['post_count'];
                $threadCount = $subForumStats['thread_count'];

                $db->query("INSERT INTO $paTableName VALUES ($userId, $nodeId, $postCount, $threadCount)");               
            }            
        }                                
    }
        
    public static function getPostAreasTableName ()
    {
        return \XF::em()->getEntityStructure('Awedo\PostAreas:PostAreas')->table;        
    }    
}
