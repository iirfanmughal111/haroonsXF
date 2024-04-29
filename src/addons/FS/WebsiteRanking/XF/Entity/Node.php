<?php

namespace FS\WebsiteRanking\XF\Entity;

use XF\Mvc\Entity\Structure;
use InvalidArgumentException;

class Node extends XFCP_Node
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        
        $columns = [
            'issue_count' => ['type'=> self::UINT,'default'=>0],
            
            'pending_count' => ['type'=> self::UINT,'default'=>0],
            'solved_count' => ['type'=> self::UINT,'default'=>0],
            'unsolved_count' => ['type'=> self::UINT,'default'=>0],
            
            'solved_percentage' => ['type'=> self::FLOAT,'default'=>0],
            'unsolved_percentage' => ['type'=> self::FLOAT,'default'=>0],
            'pending_percentage' => ['type'=> self::FLOAT,'default'=>0]
        ];
        
        $structure->columns += $columns;

        $structure->relations += [
            'Forum' => [
                'entity' => 'XF:Forum',
                'type' => self::TO_ONE,
                'conditions' => 'node_id',
                'primary' => true
            ]
        ];

        return $structure;
    }

//    public function getViewCounts()
//    {
//        $db = \XF::db();
//
//        $res =  $db->fetchAll(
//            "SELECT SUM(`view_count`) AS total_sum
//            FROM xf_thread
//            WHERE node_id  = ?
//		",
//            [
//                $this->node_id,
//            ]
//        );
//
//        return intval($res['0']['total_sum']);
//    }

//    public function getRandomColor()
//    {
//        return sprintf('%06X', mt_rand(0, 0xFFFFFF));
//        // mt_srand((float)microtime() * 1000000);
//        // $c = '';
//        // while (strlen($c) < 6) {
//        //     $c .= sprintf("%02X", mt_rand(0, 255));
//        // }
//        // return $c;
//    }
}
