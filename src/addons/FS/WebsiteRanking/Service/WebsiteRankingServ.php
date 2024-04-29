<?php

namespace FS\WebsiteRanking\Service;

use XF\Mvc\FormAction;

class WebsiteRankingServ extends \XF\Service\AbstractService
{

//    public function getSiteRanking($subNodeIds)
//    {
//        $highestResolutionPercentage = 0;
//        $lowestResolutionPercentage = 100;
//        $totalResolvedThreads = 0;
//        $totalThreads = 0;
//        $nodeIdWithHighestResolution = null;
//        $nodeIdWithLowestResolution = null;
//        $nodeIdWithMostThreads = null;
//        $maxThreadCount = 0;
//
//        foreach ($subNodeIds as $nodeId) {
//            $threadCount = \XF::db()->fetchOne("
//        SELECT COUNT(*) 
//        FROM xf_thread 
//        WHERE node_id = ?
//    ", $nodeId);
//
//            if ($threadCount > 0) {
//                $resolvedThreadCount = \XF::db()->fetchOne("
//            SELECT COUNT(*) 
//            FROM xf_thread 
//            WHERE node_id = ? 
//            AND issue_status = 1
//        ", $nodeId);
//
//                $resolutionPercentage = round(($resolvedThreadCount / $threadCount) * 100);
//
//                if ($resolutionPercentage > $highestResolutionPercentage) {
//                    $highestResolutionPercentage = $resolutionPercentage;
//                    $nodeIdWithHighestResolution = $nodeId;
//                }
//
//                if ($resolutionPercentage < $lowestResolutionPercentage) {
//                    $lowestResolutionPercentage = $resolutionPercentage;
//                    $nodeIdWithLowestResolution = $nodeId;
//                }
//
//                $totalResolvedThreads += $resolvedThreadCount;
//                $totalThreads += $threadCount;
//
//                if ($threadCount > $maxThreadCount) {
//                    $maxThreadCount = $threadCount;
//                    $nodeIdWithMostThreads = $nodeId;
//                }
//            }
//        }
//
//        $totalResolutionPercentage = round(($totalResolvedThreads / $totalThreads) * 100);
//
//        $viewParams = [
//            "highPercen" => $highestResolutionPercentage ?: 0,
//            "lowPercen" => $lowestResolutionPercentage ?: 0,
//            "totalPercen" => $totalResolutionPercentage ?: 0,
//            "mostcomplains" => $maxThreadCount ?: 0,
//            "highPercenNode" => $nodeIdWithHighestResolution ? $this->em()->findOne('XF:Node', ['node_id' => $nodeIdWithHighestResolution]) : [],
//            "lowPercenNode" => $nodeIdWithLowestResolution ? $this->em()->findOne('XF:Node', ['node_id' => $nodeIdWithLowestResolution]) : [],
//            "nodeMostComplains" => $nodeIdWithMostThreads ? $this->em()->findOne('XF:Node', ['node_id' => $nodeIdWithMostThreads]) : [],
//        ];
//
//        return $viewParams;
//    }
}
