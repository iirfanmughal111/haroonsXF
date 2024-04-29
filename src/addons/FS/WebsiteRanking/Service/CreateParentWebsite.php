<?php

namespace FS\WebsiteRanking\Service;

use XF\Mvc\FormAction;

class CreateParentWebsite extends \XF\Service\AbstractService
{
    public $nodeId;

    public function createWebsite()
    {
        $node = \xf::app()->em()->create('XF:Node');
        $node->node_type_id = "Category";
        $this->createWebsiteProcess($node)->run();
        
        return $node;
    }

    protected function createWebsiteProcess(\XF\Entity\Node $node)
    {
        $form = \xf::app()->formAction();

        $input = [
            'node' => [
                'title' => 'Website Ranking',
                'node_name' => '',
                'description' => '',
                'parent_node_id' => '',
                'display_order' => '',
                'display_in_list' => false,
                'style_id' => '',
                'navigation_id' => 'fsWebsiteRanking'
            ]
        ];

        $data = $node->getDataRelationOrDefault(false);
        $node->addCascadedSave($data);

        $form->basicEntitySave($node, $input['node']);
        $this->saveTypeData($form, $node, $data);

        return $form;
    }

    protected function getForumTypeHandlerForAddEdit(\XF\Entity\Node $node)
    {
        /** @var \XF\Entity\Forum $forum */
        $forum = $node->getDataRelationOrDefault(false);

        if (!$node->exists()) {

            return $this->app->forumType("discussion", false);
        } else {
            return $forum->TypeHandler;
        }
    }

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
    {
    }

    protected function filterIndexCriteria()
    {
        $criteria = [];

        $input =

            [
                'max_days_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'max_days_last_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_replies' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_reaction_score' => [
                    'enabled' => false,
                    'value' => 0
                ]
            ];

        foreach ($input as $rule => $criterion) {
            if (!$criterion['enabled']) {
                continue;
            }

            $criteria[$rule] = $criterion['value'];
        }

        return $criteria;
    }

    public function updateWebsiteOption($nodeId)
    {
        $optionIndex = \xf::app()->finder('XF:Option')->where('option_id', 'fs_web_ranking_parent_web_id')->fetchOne();

        if ($optionIndex) {

            $optionIndex->option_value = $nodeId;
            $optionIndex->save();
        }
    }

    public function permissionRebuild()
    {

        // $userGroup = \xf::app()->finder('XF:UserGroup')->whereId(2)->fetchOne();


        // $permissions = [
        //     'general' => [

        //         'viewNode' => 'content_allow',
        //     ],
        //     'forum' => [

        //         'postThread' => 'content_allow',
        //         'postReply' => 'content_allow',
        //     ]
        // ];



        // $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
        // $permissionUpdater->setContent("node", $node->node_id)->setUserGroup($userGroup);
        // $permissionUpdater->updatePermissions($permissions);

        $userGroups = $this->getUserGroupRepo()->findUserGroupsForList()->fetch();

        if (count($userGroups)) {
            //
            $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
            foreach ($userGroups as $group) {

                $permissionUpdater->setUserGroup($group)->setGlobal();
                if (\xf::app()->container()->isCached('permission.builder')) {
                    \xf::app()->permissionBuilder()->refreshData();
                }

                $permissionUpdater->triggerCacheRebuild();
            }
        }
    }

    public function getUserGroupRepo()
    {
        return \xf::app()->repository('XF:UserGroup');
    }
}
