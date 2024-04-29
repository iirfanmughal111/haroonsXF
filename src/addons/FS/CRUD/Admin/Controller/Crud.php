<?php

namespace FS\CRUD\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Crud extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        $finder = $this->finder('FS\CRUD:Crud')->order('id', 'DESC');

        $page = $params->page;
        $perPage = 15;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'data' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            'totalReturn' => count($finder->fetch()),
        ];

        return $this->view('FS\CRUD:Crud\Index', 'fs_crud_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\CRUD:Crud');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\CRUD\Entity\Crud $data */
        $data = $this->assertDataExists($params->id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\CRUD\Entity\Crud $data)
    {

        $viewParams = [
            'data' => $data,
        ];

        return $this->view('FS\CRUD:Crud\Add', 'fs_crud_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->id) {
            $dataEditAdd = $this->assertDataExists($params->id);
        } else {
            $dataEditAdd = $this->em()->create('FS\CRUD:Crud');
        }

        $this->dataSaveProcess($dataEditAdd)->run();

        return $this->redirect($this->buildLink('crud'));
    }

    protected function dataSaveProcess(\FS\CRUD\Entity\Crud $data)
    {
        $input = $this->filterInputs();

        $form = $this->formAction();
        $form->basicEntitySave($data, $input);

        return $form;

        // $data->name = $input['name'];
        // $data->class = $input['class'];
        // $data->rollNo = $input['rollNo'];
        // $data->save();
    }

    protected function filterInputs()
    {
        $input = $this->filter([
            'name' => 'str',
            'class' => 'str',
            'rollNo' => 'int',
        ]);

        if ($input['name'] == '' || $input['class'] == '' || $input['rollNo'] <= 0) {
            throw $this->exception($this->error(\XF::phraseDeferred('please_complete_required_fields')));
        }

        return $input;
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('crud/delete', $replyExists),
            null,
            $this->buildLink('crud'),
            "{$replyExists->name}"
        );
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\UpgradeUserGroup\Entity\UpgradeUserGroup
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\CRUD:Crud', $id, $extraWith, $phraseKey);
    }
}
