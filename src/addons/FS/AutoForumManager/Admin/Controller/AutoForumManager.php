<?php

namespace FS\AutoForumManager\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class AutoForumManager extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        $finder = $this->finder('FS\AutoForumManager:AutoForumManager')->order('forum_manage_id', 'DESC');


        $page = $params->page;
        $perPage = 15;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'autoForumManager' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            'totalReturn' => count($finder->fetch()),
        ];

        return $this->view('FS\AutoForumManager:AutoForumManager\Index', 'fs_auto_forum_manage_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\AutoForumManager:AutoForumManager');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\AutoForumManager\Entity\AutoForumManager $data */
        $data = $this->assertDataExists($params->forum_manage_id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\AutoForumManager\Entity\AutoForumManager $data)
    {
        $searcher = $this->searcher('XF:Thread');

        $viewParams = [
            'autoForumManager' => $data,
            'allAdmins' => $this->finder('XF:User')->where('is_admin', 1)->fetch(),
        ] + $searcher->getFormData();

        return $this->view('FS\AutoForumManager:AutoForumManager\Add', 'fs_auto_forum_manage_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->forum_manage_id) {
            $editAdd = $this->assertDataExists($params->forum_manage_id);
        } else {
            $editAdd = $this->em()->create('FS\AutoForumManager:AutoForumManager');
        }

        $this->saveProcess($editAdd);

        return $this->redirect($this->buildLink('forumMng'));
    }

    protected function saveProcess(\FS\AutoForumManager\Entity\AutoForumManager $data)
    {
        if (empty($data->forum_manage_id)) {
            $input = $this->filterInputs();

            foreach ($input['listData'] as $node_id) {

                $insert = $this->entryExist($input['admin_id'], $node_id);
                $this->saveData($insert, $node_id, $input);
            }
        } else {
            $input = $this->filterInputs();

            $this->saveData($data, $input['listData']['0'], $input);
        }
    }

    protected function filterInputs()
    {
        $input = $this->filter([
            'listData' => 'array',
            'admin_id' => 'str',
            'total_days' => 'int',
        ]);

        if ($input['admin_id'] != 0 && $input['total_days'] != 0) {
            return $input;
        }

        throw $this->exception(
            $this->notFound(\XF::phrase("fs_auto_filled_required_fields"))
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->forum_manage_id, ['User']);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('forumMng/delete', $replyExists),
            null,
            $this->buildLink('forumMng'),
            "{$replyExists->User->username}"
        );
    }

    protected function entryExist($admin_id, $node_id)
    {
        $finder = $this->finder('FS\AutoForumManager:AutoForumManager')->where('admin_id', $admin_id)->where('node_id', $node_id)->fetchOne();

        if ($finder) {
            return $finder;
        } else {
            return $this->em()->create('FS\AutoForumManager:AutoForumManager');
        }
    }

    protected function saveData($data, $node_id, $input)
    {
        $data->admin_id = $input['admin_id'];
        $data->node_id = $node_id;
        $data->last_login_days = $input['total_days'];
        $data->save();
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\AutoForumManager\Entity\AutoForumManager
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\AutoForumManager:AutoForumManager', $id, $extraWith, $phraseKey);
    }
}
