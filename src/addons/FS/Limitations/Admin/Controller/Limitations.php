<?php

namespace FS\Limitations\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Limitations extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        $finder = $this->finder('FS\Limitations:Limitations')->order('id', 'DESC');

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

        return $this->view('FS\Limitations:Limitations\Index', 'fs_limitations_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\Limitations:Limitations');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\Limitations\Entity\Limitations $data */
        $data = $this->assertDataExists($params->id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\Limitations\Entity\Limitations $data)
    {
        $viewParams = [
            'data' => $data,
            'userGroups' => $this->getUserGroupRepo()->findUserGroupsForList()->fetch(),
        ];

        return $this->view('FS\Limitations:Limitations\Add', 'fs_limitations_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->id) {
            $dataEditAdd = $this->assertDataExists($params->id);
        } else {
            $input = $this->filterInputs();

            $exist = $this->em()->findOne('FS\Limitations:Limitations', ['user_group_id' => $input['user_group_id']]);

            if ($exist) {
                throw $this->exception($this->error(\XF::phraseDeferred('fs_limitations_user_group_already_exist')));
            }

            $dataEditAdd = $this->em()->create('FS\Limitations:Limitations');
        }

        $this->dataSaveProcess($dataEditAdd)->run();

        return $this->redirect($this->buildLink('limitations'));
    }

    protected function dataSaveProcess(\FS\Limitations\Entity\Limitations $data)
    {
        $input = $this->filterInputs();

        $form = $this->formAction();
        $form->basicEntitySave($data, $input);

        return $form;
    }

    protected function filterInputs()
    {
        $input = $this->filter([
            'user_group_id' => 'int',
            'node_ids' => 'str',
            'daily_ads' => 'int',
            'daily_repost' => 'int',
        ]);

        if ($input['node_ids'] == '' || $input['user_group_id'] <= 0  || $input['daily_ads'] <= 0  || $input['daily_repost'] < 0) {
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
            $this->buildLink('limitations/delete', $replyExists),
            null,
            $this->buildLink('limitations'),
            "{$replyExists->UserGroup->title}"
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
        return $this->assertRecordExists('FS\Limitations:Limitations', $id, $extraWith, $phraseKey);
    }

    /**
     * @return \XF\Repository\UserGroup
     */
    protected function getUserGroupRepo()
    {
        return $this->repository('XF:UserGroup');
    }
}
