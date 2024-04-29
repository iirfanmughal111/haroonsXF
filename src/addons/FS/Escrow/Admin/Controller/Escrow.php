<?php

namespace FS\Escrow\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class Escrow extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {

        if ($this->filter('search', 'uint')) {
            $finder = $this->getSearchFinder();
            // if (count($finder->getConditions()) == 0) {
            //     return $this->error(\XF::phrase('please_complete_required_field'));
            // }
        } else {
            $finder = $this->finder('FS\Escrow:Escrow');
        }

        $page = $this->filterPage($params->page);
        $perPage = 25;
        $finder->limitByPage($page, $perPage);

        $viewpParams = [
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'escrows' => $finder->fetch(),
            'conditions' => $this->filterSearchConditions(),
        ];

        return $this->view('FS\Escrow:Escrow', 'fs_escrow_admin_list', $viewpParams);
    }

    protected function getSearchFinder()
    {
        $conditions = $this->filterSearchConditions();
        $finder = $this->finder('FS\Escrow:Escrow');

        if ($conditions['fs_escrow_start_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_start_username'])->fetchOne();
            if ($User) {
                $finder->where('user_id', $User['user_id']);
            }
        }
        if ($conditions['fs_escrow_mentioned_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_mentioned_username'])->fetchOne();
            if ($User) {
                $finder->where('to_user', $User['user_id']);
            }
        }

        if ($conditions['fs_escrow_status'] != 'all') {
            if (intval($conditions['fs_escrow_status']) >= 0 && intval($conditions['fs_escrow_status']) <= 4) {
                $finder->where('escrow_status', intval($conditions['fs_escrow_status']));
            }
        }


        return $finder;
    }



    public function filterPage($page = 0, $inputName = 'page')
    {
        return max(1, intval($page) ?: $this->filter($inputName, 'uint'));
    }

    public function actionRefineSearch(ParameterBag $params)
    {

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
        ];
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_search_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        return $this->filter([
            'fs_escrow_username' => 'str',
            'fs_escrow_status' => 'str',
            'fs_escrow_start_username' => 'str',
            'fs_escrow_mentioned_username' => 'str',

        ]);
    }
}
