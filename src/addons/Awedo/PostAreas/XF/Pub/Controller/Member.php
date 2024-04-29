<?php

/** 
  * @author Thomas Braunberger
  */


namespace Awedo\PostAreas\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member
{
    
    public function actionPostAreas (ParameterBag $params)
    {   
        $user = $this->assertViewableUser($params->user_id);

      
        
        $class = \XF::extendClass('Awedo\PostAreas\Model\PostAndThreadStats');

        
        
        /* @var $stats \Awedo\PostAreas\Model\PostAndThreadStats */
        $stats = new $class;
        
        if ($this->options()->apaUseCache)
        {
            $postAreas = $stats->getPostAndThreadCountsFromCache($params->user_id);
        }
        else
        {
            $postAreas = $stats->getPostAndThreadCounts($params->user_id);
        }
                            
        // filter out stats for which the current user has no view perm 
        foreach ($postAreas as $forumData)
        {                
            if (\XF::visitor()->hasNodePermission($forumData['node_id'], 'view'))
            {
                $postAreasFiltered[] = $forumData;
            }                    
        }
        
        if (!isset($postAreasFiltered))
        {
            $postAreasFiltered = array();
        }
        
        $limit = $this->options()->apaMaxForums;
        
        if ($limit)
        {                        
            $postAreasFiltered = array_slice($postAreasFiltered, 0, $limit);
        }        
        
        $viewParams = [ 
            'user' => $user,
            'postAreas' => $postAreasFiltered,
            'hasCreatedAThread' => $this->_containsThreadCount($postAreas)
        ];                
        
        return $this->view('Awedo\PostArea:PostAreas', 'awedo_postAreas',$viewParams);                
    }
        
        
    /**
     * Returns true if there is at least one forum with a thread count
     * 
     * @param array $postAreas
     * @return boolean
     */
    protected function _containsThreadCount ($postAreas)
    {
        foreach ($postAreas as $postArea)
        {
            if (array_key_exists('thread_count', $postArea))
            {
                return true;
            }
        }        
        
        return false;                
    }
    
}