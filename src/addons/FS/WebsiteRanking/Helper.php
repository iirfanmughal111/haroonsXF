<?php

namespace FS\WebsiteRanking;

use XF\Mvc\Entity\Entity;
use XF\Template\Templater;

class Helper
{
    
        public static function calculateIssuePercentageOfNode(\XF\Entity\Forum $forum)
        {
            $query = "SELECT
                        node_id,
                        COUNT(*) AS total,
                        SUM(issue_status = 0) AS pending_count,
                        SUM(issue_status = 1) AS solved_count,
                        SUM(issue_status = 2) AS unsolved_count,
                        ROUND((SUM(issue_status = 0) / COUNT(*)) * 100, 2) AS pending_percentage,
                        ROUND((SUM(issue_status = 1) / COUNT(*)) * 100, 2) AS solved_percentage,
                        ROUND((SUM(issue_status = 2) / COUNT(*)) * 100, 2) AS unsolved_percentage
                    FROM
                        xf_thread
                    WHERE
                        node_id = $forum->node_id AND discussion_state = 'visible'
                    GROUP BY
                        node_id";


            $queryResult = \XF::db()->fetchRow($query);


    //        $result= \XF::em()->instantiateEntity('XF:Forum', $result);
    //        $result = new ArrayCollection($result);
            


            if($queryResult)
            {
                $forum->Node->fastUpdate([
                                        'issue_count' => $queryResult['total'],

                                        'pending_count' => $queryResult['pending_count'],
                                        'solved_count' => $queryResult['solved_count'],
                                        'unsolved_count' => $queryResult['unsolved_count'],

                                        'pending_percentage' => $queryResult['pending_percentage'],
                                        'solved_percentage' => $queryResult['solved_percentage'],
                                        'unsolved_percentage' => $queryResult['unsolved_percentage'], 
                                    ]);
            }
        }
        
        
        
        public static function isApplicableForum(\XF\Entity\Forum $forum)
        {
            $forumParentNodeId = $forum->Node->parent_node_id;
            
            if(\XF::options()->fs_web_ranking_parent_web_id && ($forumParentNodeId == \XF::options()->fs_web_ranking_parent_web_id))
            {
                return true;
            }

            return false;   
        }

}