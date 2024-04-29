<?php

namespace FS\NodeUrl\XF\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class NodeUrl extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        $data = $this->finder('XF:Node')->where('node_url', '<>', '')->order('node_id', 'DESC');

        $page = $params->page;
        $perPage = 5;

        $data->limitByPage($page, $perPage);

        $viewParams = [
            'data' => $data->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $data->total()
        ];

        return $this->view('FS\NodeUrl\XF:NodeUrl\Index', 'node_url_index', $viewParams);
    }

    public function actionAddView()
    {

        $searcher = $this->searcher('XF:Thread');

        $viewParams = [] + $searcher->getFormData();
        return $this->view('FS\NodeUrl\XF:NodeUrl\Index', 'node_url_add', $viewParams);
    }

    public function actionSave()
    {


        $input = $this->filter([
            'listData' => 'array',
            'nodeUrl' => 'str'
        ]);

        foreach ($input['listData'] as $value) {

            $this->dataAddEdit($value, $input['nodeUrl']);
        }

        return $this->redirect($this->buildLink('nodeUrl'));
    }

    public function actionEditView(ParameterBag $params)
    {

        $data = $this->finder('XF:Node')->whereId($params->node_id);

        $viewParams = [
            'data' => $data->fetchOne(),
        ];

        return $this->view('FS\NodeUrl\XF:NodeUrl\EditView', 'node_url_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {

        $input = $this->filter([
            'nodeUrl' => 'str'
        ]);

        $this->dataAddEdit($params->node_id, $input['nodeUrl']);

        return $this->redirect($this->buildLink('nodeUrl'));
    }

    public function actionDeleteRecord(ParameterBag $params)
    {
        $dataExists = $this->assertDataExists($params->node_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->dataAddEdit($params->node_id, '');

            return $this->redirect($this->buildLink('nodeUrl'));
        }
        return $plugin->actionDelete(
            $dataExists,
            $this->buildLink('nodeUrl/delete-record', $dataExists),
            null,
            $this->buildLink('nodeUrl'),
            $dataExists->node_url
        );
    }

    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('XF:Node', $id, $extraWith, $phraseKey);
    }

    protected function dataAddEdit($id, $url)
    {
        $nodes = $this->finder('XF:Node')->whereId($id);

        $node = $nodes->fetchOne();

        if (empty($url)) {
            $node->node_url = '';
        } else {
            $node->node_url = $url;
        }

        $node->save();

        return $node;
    }
}
