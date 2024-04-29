<?php

namespace FS\WebsiteRanking\Service;

use XF\Mvc\FormAction;

class NodeAddEdit extends \XF\Service\AbstractService
{
    public $nodeId;

    public function createNode(\XF\Entity\Node $node)
    {
        // $node = \xf::app()->em()->create('XF:Node');
        $node->node_type_id = "Forum";
        $this->createNodeProcess($node)->run();
        
        $this->permissionRebuild();
        
        return $node;
    }

    protected function createNodeProcess(\XF\Entity\Node $node)
    {
        $form = \xf::app()->formAction();

        $getTitleDesc = \xf::app()->request()->filter([
            'title' => 'str',
            'description' => 'str',
        ]);

        $input = [
            'node' => [
                'title' => $getTitleDesc['title'],
                'node_name' => '',
                'description' => $getTitleDesc['description'],
                'parent_node_id' => \XF::options()->fs_web_ranking_parent_web_id,
                'display_order' => 1,
                'display_in_list' => 0,
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
        $forumType = $this->getForumTypeHandlerForAddEdit($node);
        if (!$forumType) {
            $form->logError(\XF::phrase('forum_type_handler_not_found'), 'forum_type_id');
            return;
        }

        $forumInput = [
            'allow_posting' => 1,
            'moderate_threads' => 0,
            'moderate_replies' => 0,
            'count_messages' => 0,
            'find_new' => 0,
            'allowed_watch_notifications' => 'all',
            'default_sort_order' => 'last_post_date',
            'default_sort_direction' => 'desc',
            'list_date_limit_days' => 0,
            'default_prefix_id' => 0,
            'require_prefix' => false,
            'min_tags' => 0,
            'allow_index' => 'allow'
        ];

        $forumInput['index_criteria'] = $this->filterIndexCriteria();

        /** @var \XF\Entity\Forum $data */
        $data->bulkSet($forumInput);
        $data->forum_type_id = $forumType->getTypeId();

        $typeConfig = $forumType->setupTypeConfigSave($form, $node, $data, \xf::app()->request);
        if ($typeConfig instanceof \XF\Mvc\Entity\ArrayValidator) {
            if ($typeConfig->hasErrors()) {
                $form->logErrors($typeConfig->getErrors());
            }
            $typeConfig = $typeConfig->getValuesForced();
        }

        $data->type_config = $typeConfig;


        $prefixIds = \XF::app()->finder('XF:ThreadPrefix')->pluckfrom('prefix_id')->fetch()->toArray();

        $form->complete(function () use ($data, $prefixIds) {
            /** @var \XF\Repository\ForumPrefix $repo */
            $repo = $this->repository('XF:ForumPrefix');
            $repo->updateContentAssociations($data->node_id, $prefixIds);
        });
        
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
