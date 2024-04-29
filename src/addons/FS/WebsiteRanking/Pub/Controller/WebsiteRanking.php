<?php

namespace FS\WebsiteRanking\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;
use db;

class WebsiteRanking extends AbstractController
{

    public $siteUrl = "web-ranking";

    protected function preDispatchController($action, ParameterBag $params)
    {
        if (!\xf::visitor()->hasPermission('fs_website_ranking', 'check')) {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }
    }
    
    
    public function getOverAllSolvedAverage()
    {
            $db = \XF::db();
            
            $query = "SELECT
                        AVG(solved_percentage) AS solvedAverage
                        FROM xf_node where issue_count != 0";

            $solvedAverage = $db->fetchOne($query);
            
            return $solvedAverage;
    }

    

    public function actionIndex(ParameterBag $params)
    {
                $this->checkUser();

                $selfRoute = $this->siteUrl;
		$this->assertCanonicalUrl($this->buildLink($selfRoute));

		$nodeRepo = $this->getNodeRepo();
		$finder = $nodeRepo->getFullWebsiteNodeList();
                
                
        //  <!--------------------------- filters ----------------------->
                
                $filter = $this->filter('fs_wr_complains', 'str');
                
                if ($this->filter('search', 'uint') || $filter) 
                {
                     $nodes = $finder->order($filter,'desc');
                }
                
        //  <!----------------------------------------------------------->
                
                


                $nodes = $finder->fetch();
                
		$nodeTree = $nodeRepo->createNodeTree($nodes);
		$nodeExtras = $nodeRepo->getNodeListExtras($nodeTree);
                
                
                
                $limit = $this->options()->fs_wr_numOfSites;
                
                $finder = $this->Finder('XF:Node')->where('issue_count','!=',0);
                                   
                $solvedFinder = clone $finder;
                $unsolvedFinder = clone $finder;
                $pendingFinder = clone $finder;
                
                $solvedPercentageNodes = $solvedFinder->order('solved_percentage','Desc')->fetch($limit);
                $unsolvedPercentageNodes = $unsolvedFinder->order('unsolved_percentage','Desc')->fetch($limit);
                $mostCompainsNodes = $pendingFinder->order('issue_count','Desc')->fetch($limit);
                
                $overAllSolvedAverage = $this->getOverAllSolvedAverage();
                

		$viewParams = [
			'nodeTree' => $nodeTree,
			'nodeExtras' => $nodeExtras,
			'selfRoute' => $selfRoute,
                    
                        'solvedPercentageNodes' => $solvedPercentageNodes,
                        'unsolvedPercentageNodes' => $unsolvedPercentageNodes,
                        'mostCompainsNodes' => $mostCompainsNodes,
                    
                        'overAllsolvedAverage' => $overAllSolvedAverage,

                        'filters' => $this->filterSearchConditions()
                        
		];
        
		return $this->view('XF:Forum\Listing', 'fs_website_ranking_forum_list', $viewParams);
        
    }



    
// <!-------------------------------------------------------------!>
    public function actionRefineSearch(ParameterBag $params)
    {
        $viewParams = [
            'filters' => $this->filterSearchConditions(),
        ];

        return $this->view('FS\WebRanking:PubController\RefineSearch', 'fs_web_ranking_search_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        return $this->filter([
            'fs_wr_complains' => 'str',
        ]);
    }

    protected function checkUser()
    {
        if (!\XF::visitor()->user_id) {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }
    }
    
// <!-------------------------------------------------------------!>
    
    protected function getNodeRepo()
    {
            return $this->repository('XF:Node');
    }
}
