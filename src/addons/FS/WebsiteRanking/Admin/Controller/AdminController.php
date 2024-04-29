<?php

namespace FS\WebsiteRanking\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class AdminController extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        $finder = \xf::finder('XF:Node')->where("parent_node_id", \XF::options()->fs_web_ranking_parent_web_id);

        $page = $this->filterPage($params->page);
        $perPage = 25;
        $finder->limitByPage($page, $perPage);

        $viewpParams = [
            'node' => \XF::options()->fs_web_ranking_parent_web_id ? $finder->order('node_id', 'DESC')->fetch() : '',
            'page' => $page,
            'total' => $finder->total(),
            'totalReturn' => count($finder->fetch()),
            'perPage' => $perPage,
        ];

        return $this->view('FS\WebsiteRanking:AdminController\Index', 'fs_web_ranking_index', $viewpParams);
    }

    public function actionAdd()
    {
        $node = $this->em()->create('XF:Node');
        return $this->actionAddEdit($node);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\UpgradeUserGroup\Entity\UpgradeUserGroup $data */
        $node = $this->assertDataExists($params->node_id);

        return $this->actionAddEdit($node);
    }

    public function actionAddEdit(\XF\Entity\Node $node)
    {
        $viewParams = [
            'node' => $node,
        ];

        return $this->view('FS\WebsiteRanking:AdminController\AddEdit', 'fs_web_ranking_addEdit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->node_id) {
            $node = $this->assertDataExists($params->node_id);
        } else {
            $node = $this->em()->create('XF:Node');
        }

        $this->saveProcess($node);

        return $this->redirect($this->buildLink('web-ranking'));
    }

    protected function saveProcess(\XF\Entity\Node $node)
    {
        $this->filterInputs();

        $nodeService = \xf::app()->service('FS\WebsiteRanking:NodeAddEdit');

        $nodeRes = $nodeService->createNode($node);

        return $nodeRes;
    }

    protected function filterInputs()
    {
        $input = $this->filter([
            'title' => 'str',
            'description' => 'str',
        ]);

        if ($input['title'] = '') {
            throw $this->exception(
                $this->notFound(\XF::phrase("please_enter_valid_title"))
            );
        }
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->node_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('web-ranking/delete', $replyExists),
            null,
            $this->buildLink('web-ranking'),
            "{$replyExists->title}"
        );
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\Node
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('XF:Node', $id, $extraWith, $phraseKey);
    }
}
