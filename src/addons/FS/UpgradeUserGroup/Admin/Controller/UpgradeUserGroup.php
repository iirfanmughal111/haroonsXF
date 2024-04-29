<?php

namespace FS\UpgradeUserGroup\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class UpgradeUserGroup extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        $url = $this->getUrlType();

        $finder = $this->finder('FS\UpgradeUserGroup:UpgradeUserGroup')->where($this->fetchFunc())->order('usg_id', 'DESC');

        $page = $params->page;
        $perPage = 1;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'upgradeUserGroup' => $finder->fetch(),

            'urlType' => $this->getUrlType(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            'totalReturn' => count($finder->fetch()),
        ];

        return $this->view('FS\UpgradeUserGroup:UpgradeUserGroup\Index', 'fs_upgrade_usergroup_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\UpgradeUserGroup:UpgradeUserGroup');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\UpgradeUserGroup\Entity\UpgradeUserGroup $data */
        $data = $this->assertDataExists($params->usg_id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\UpgradeUserGroup\Entity\UpgradeUserGroup $data)
    {
        $viewParams = [
            'urlType' => $this->getUrlType(),
            'upgradeUserGroup' => $data,
            'userGroups' => $this->em()->getRepository('XF:UserGroup')->getUserGroupTitlePairs(),
        ];

        return $this->view('FS\UpgradeUserGroup:UpgradeUserGroup\Add', 'fs_upgrade_usergroup_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->usg_id) {
            $usergroupEditAdd = $this->finder('FS\UpgradeUserGroup:UpgradeUserGroup')->where('usg_id', $params->usg_id)->where($this->fetchFunc())->fetchOne();
        } else {
            $input = $this->filterUsergroupInputs();
            $find = $this->finder('FS\UpgradeUserGroup:UpgradeUserGroup')->where('current_userGroup', $input['sl_ug_id'])->where($this->fetchFunc())
                ->fetchOne();
            if ($find) {
                $usergroupEditAdd = $find;
            } else {
                $usergroupEditAdd = $this->em()->create('FS\UpgradeUserGroup:UpgradeUserGroup');
            }
        }

        $this->usergroupSaveProcess($usergroupEditAdd);

        return $this->redirect($this->buildLink($this->getUrlType()));
    }

    protected function usergroupSaveProcess(\FS\UpgradeUserGroup\Entity\UpgradeUserGroup $userGroupData)
    {
        $input = $this->filterUsergroupInputs();

        $userGroupData->current_userGroup = $input['sl_ug_id'];
        if ($this->getUrlType() == "upgradeGroup") {
            $userGroupData->message_count = $input['total_message'];
        } else {
            $userGroupData->last_login = $input['last_login'];
        }
        $userGroupData->upgrade_userGroup = $input['up_ug_id'];
        $userGroupData->save();
    }

    protected function filterUsergroupInputs()
    {

        if ($this->getUrlType() == "upgradeGroup") {
            $input = $this->filter([
                'sl_ug_id' => 'int',
                'total_message' => 'int',
                'up_ug_id' => 'int',
            ]);

            if ($input['sl_ug_id'] != 0 && $input['total_message'] != 0 && $input['up_ug_id'] != 0) {
                if ($input['sl_ug_id'] != $input['up_ug_id']) {
                    return $input;
                } else {
                    throw $this->exception(
                        $this->notFound(\XF::phrase("fs_select_different_usergroups"))
                    );
                }
            }
        } else {
            $input = $this->filter([
                'sl_ug_id' => 'int',
                'last_login' => 'int',
                'up_ug_id' => 'int',
            ]);

            if ($input['sl_ug_id'] != 0 && $input['last_login'] != 0 && $input['up_ug_id'] != 0) {
                if ($input['sl_ug_id'] != $input['up_ug_id']) {
                    return $input;
                } else {
                    throw $this->exception(
                        $this->notFound(\XF::phrase("fs_select_different_usergroups"))
                    );
                }
            }
        }

        throw $this->exception(
            $this->notFound(\XF::phrase("fs_filled_reqired_fields"))
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->usg_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink($this->getUrlType() . '/delete', $replyExists),
            null,
            $this->buildLink($this->getUrlType()),
            "{$replyExists->UserGroup->title}"
        );
    }

    protected function getUrlType()
    {
        $current_route = explode("/", $_SERVER['QUERY_STRING']);

        return $current_route[0];
    }

    protected function fetchFunc()
    {

        if ($this->getUrlType() == "upgradeGroup") {
            return ['last_login', null];
        } else {
            return ['message_count', null];
        }
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
        return $this->assertRecordExists('FS\UpgradeUserGroup:UpgradeUserGroup', $id, $extraWith, $phraseKey);
    }
}
