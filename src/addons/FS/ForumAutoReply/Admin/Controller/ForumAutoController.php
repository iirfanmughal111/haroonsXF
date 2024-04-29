<?php

namespace FS\ForumAutoReply\Admin\Controller;

use Laminas\Stdlib\Parameters;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class ForumAutoController extends AbstractController {

    public function actionIndex(ParameterBag $params) {
        
        $page = $this->filterPage();

        $perPage = 15;

        $from = (($perPage * $page) - $perPage) + 1;
        $start = $from - 1;
        $limit = $perPage;

        $result = $this->pagination($start, $limit);

        $prefixListData = $this->getPrefixRepo();

        $viewParams = [
            'Messages' => $result['data'],
            'nodeTree' => $this->getNodesRepo(),
            'userGroups' => $this->getUserGroupRepo()->findUserGroupsForList()->fetch(),
            'prefixGroups' => $prefixListData['prefixGroups'],
            'prefixesGrouped' => $prefixListData['prefixesGrouped'],
            'page' => $page,
            'perPage' => $perPage,
            'totalReturn' => $result['totalReturn'],
            'total' => $result['total']
        ];

        return $this->view('FS\ForumAutoReply:ForumAutoController\Index', 'forum_auto_reply_all', $viewParams);
    }

    public function actionAdd() {
        $message = $this->em()->create('FS\ForumAutoReply:ForumAutoReply');
        return $this->actionAddEdit($message);
    }

    public function actionEdit(ParameterBag $params) {
        /** @var \FS\ForumAutoReply\Entity\ForumAutoReply $message */
        $message = $this->assertMessageExists($params->message_id);
        
       
        return $this->messageAddEdit($message);
    }

    public function actionEditSingle(ParameterBag $params) {
        /** @var \FS\ForumAutoReply\Entity\ForumAutoReply $message */
        $message = $this->assertMessageExists($params->message_id);

        $userNames = $this->noMatchUserNames($message['user_id']);

        $viewParams = [
            'message' => $message,
            'userNames' => $userNames['no_match_user_names'],
        ];

        return $this->view('FS\ForumAutoReply:ForumAutoController\EditSingle', 'forum_auto_reply_edit_single', $viewParams);
    }

    public function actionEditSave(ParameterBag $params) {
        $input = $this->filterMessageInputs();
        $this->isExistedUser($input['no_match_users']);

        /** @var \FS\ForumAutoReply\Entity\ForumAutoReply $message */
        $message = $this->assertMessageExists($params->message_id);

        foreach ($input['words'] as $key => $value) {

            if ($value && $input['messages'][$key] != '') {

                $users = $this->isExistedUser($input['from_users'][$key]);

                $message->word = $value;
                $message->message = $input['messages'][$key];
                $message->user_id = $users['no_match_user_ids'];

                $message->save();
            }
        }

        return $this->messageAddEdit($message);
    }

    public function actionAddEdit(\FS\ForumAutoReply\Entity\ForumAutoReply $message) {

        $prefixListData = $this->getPrefixRepo();

        $viewParams = [
            'nodeTree' => $this->getNodesRepo(),
            'userGroups' => $this->getUserGroupRepo()->findUserGroupsForList()->fetch(),
            'prefixGroups' => $prefixListData['prefixGroups'],
            'prefixesGrouped' => $prefixListData['prefixesGrouped'],
        ];

        return $this->view('FS\ForumAutoReply:ForumAutoController\Add', 'forum_auto_reply_add', $viewParams);
    }

    protected function messageAddEdit(\FS\ForumAutoReply\Entity\ForumAutoReply $message) {
        $data = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $message['node_id'])->where('prefix_id', '!=', NULL)
                ->fetch();

        $dataGroup = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $message['node_id'])->where('prefix_id', '!=', NULL)
                ->fetchOne();

        $noDataMatch = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $message['node_id'])->where('no_match_prefix_id', '!=', NULL)->fetchOne();

        $prefixListData = $this->getPrefixRepo();

        $viewParams = [
            'message' => $message,
            'nodeId' => $message['node_id'],
            'userGroupId' => $dataGroup ? $dataGroup['user_group_id'] : '',
            'prefixId' => $dataGroup ? $dataGroup['prefix_id'] :'',
            'noDataMatch' => $noDataMatch,
            'data' => $data,
            'nodeTree' => $this->getNodesRepo(),
            'userGroups' => $this->getUserGroupRepo()->findUserGroupsForList()->fetch(),
            'prefixGroups' => $prefixListData['prefixGroups'],
            'prefixesGrouped' => $prefixListData['prefixesGrouped'],
        ];
        
    

        return $this->view('FS\ForumAutoReply:ForumAutoController\Add', 'forum_auto_reply_add', $viewParams);
    }

    public function actionFind() {
        $q = ltrim($this->filter('q', 'str', ['no-trim']));

        if ($q !== '' && utf8_strlen($q) >= 2) {
            /** @var \XF\Finder\User $userFinder */
            $userFinder = $this->finder('XF:User');

            $users = $userFinder
                    ->where('username', 'like', $userFinder->escapeLike($q, '?%'))
                    ->isValidUser(true)
                    ->fetch(10);
        } else {
            $users = [];
            $q = '';
        }

        $viewParams = [
            'q' => $q,
            'users' => $users
        ];
        return $this->view('FS\ForumAutoReply:Member\Find', '', $viewParams);
    }

    public function actionSave(ParameterBag $params) {
        
        $input = $this->filterMessageInputs();

        if ($params->message_id) {
            /** @var \FS\ForumAutoReply\Entity\ForumAutoReply $message */
            $message = $this->assertMessageExists($params->message_id);
            if ($message['node_id'] != $input['node_id']) {
                $this->isNodeExisted();
                $this->isRegisteredUser();
                $this->isExistedUser($input['no_match_users']);
                $this->preDeleteNodes($message, true);
            } else {
                $this->isRegisteredUser();
                $this->isExistedUser($input['no_match_users']);
                $this->preDeleteNodes($message, false);
            }

            $this->messageSaveProcess();
        } else {
            $this->isNodeExisted();
            $this->isRegisteredUser();
            $this->isExistedUser($input['no_match_users']);
            $this->messageSaveProcess();
        }

        return $this->redirect($this->buildLink('forumAutoReply'));
    }

    protected function messageSaveProcess() {
        
        $this->isNoMatchExisted();

        $input = $this->filterMessageInputs();

        foreach ($input['words'] as $key => $value) {

            $message = $this->em()->create('FS\ForumAutoReply:ForumAutoReply');

            if ($value && $input['messages'][$key] && $input['from_users'][$key] != '' && $input['node_id'] != 0) {

                $users = $this->isExistedUser($input['from_users'][$key]);

                $message->node_id = $input['node_id'];
                $message->word = $value;
                $message->message = $input['messages'][$key];
                $message->user_id = $users['no_match_user_ids'];
                $message->user_group_id = $input['user_group_id'];
                $message->prefix_id = $input['prefix_id'];

                $message->save();
            }
        }

        return $this->redirect($this->buildLink('forumAutoReply'));
    }

    public function actionDelete(ParameterBag $params) {
        $replyExists = $this->assertMessageExists($params->message_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
                        $replyExists,
                        $this->buildLink('forumAutoReply/delete', $replyExists),
                        null,
                        $this->buildLink('forumAutoReply'),
                        "{$replyExists->word}"
        );
    }

    public function actionDeleteAll(ParameterBag $params) {
        /** @var \FS\ForumAutoReply\Entity\ForumAutoReply $replyExists */
        $replyExists = $this->assertMessageExists($params->message_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->preDeleteNodes($replyExists, true);

            return $this->redirect($this->buildLink('forumAutoReply'));
        }

        return $plugin->actionDelete(
                        $replyExists,
                        $this->buildLink('forumAutoReply/delete-all', $replyExists),
                        $this->buildLink('forumAutoReply/edit', $replyExists),
                        $this->buildLink('forumAutoReply'),
                        \XF::phrase('fs_are_you_sure_to_delete', ['node' => $replyExists->Node->title])
        );
    }

    protected function filterMessageInputs() {
        $input = $this->filter([
            'node_id' => 'str',
            'words' => 'array',
            'messages' => 'array',
            'from_users' => 'array',
            'user_group_id' => 'str',
            'prefix_id' => 'str',
            'no_match_prefix_id' => 'str',
            'no_match_messages' => 'str',
            'no_match_users' => 'str',
        ]);

        if ($input['node_id'] && $input['words'][0] != '' && $input['messages'][0] != '' && $input['from_users'][0] != '') {

            return $input;
        }

        if ($input['node_id'] && $input['no_match_messages'] != '' && $input['no_match_users'] != '') {


            return $input;
        }


        throw $this->exception(
                        $this->notFound(\XF::phrase("fs_fil_reqired_fields"))
        );
    }

    protected function isNodeExisted() {
        $input = $this->filterMessageInputs();

        $node = null;

        $node = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $input['node_id'])->fetchOne();

        if ($node) {
            throw $this->exception($this->error(\XF::phraseDeferred('node_already_exist')));
        }
    }

    protected function isRegisteredUser() {
        $input = $this->filterMessageInputs();

        foreach ($input['from_users'] as $value) {
            if ($value != '') {
                $this->isExistedUser($value);
            }
        }
    }

    protected function isExistedUser($str) {

        $users_names = explode(" ", $str);

        $user_name = implode("", $users_names);

        $users_names = explode(",", $user_name);

        $users_ids = array();

        foreach ($users_names as $value) {
            $user = null;
            if ($value) {
                $user = $this->em()->findOne('XF:User', ['username' => $value]);

                if (!$user) {
                    throw $this->exception($this->error(\XF::phraseDeferred('requested_user_x_not_found', ['name' => $value])));
                }
                array_push($users_ids, $user['user_id']);
            }
        }

        $user_ids = implode(", ", $users_ids);

        $viewParams = [
            'no_match_user_ids' => $user_ids
        ];

        return $viewParams;
    }

    protected function isNoMatchExisted() {

        $input = $this->filterMessageInputs();

        $users = $this->isExistedUser($input['no_match_users']);

        if ($input['node_id'] && $input['no_match_prefix_id'] != 0 && $input['no_match_messages'] && $input['no_match_users'] != '') {
            $message = null;
            $message = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where([
                        'node_id' => $input['node_id'],
                        ['no_match_prefix_id', '!=', null]
                    ])->fetchOne();

            if (!$message) {
                $message = $this->em()->create('FS\ForumAutoReply:ForumAutoReply');
            }

            $message->node_id = $input['node_id'];
            $message->no_match_prefix_id = $input['no_match_prefix_id'];
            $message->no_match_message = $input['no_match_messages'];
            $message->no_match_user_ids = $users['no_match_user_ids'];

            $message->save();
        }
    }

    protected function preDeleteNodes(\FS\ForumAutoReply\Entity\ForumAutoReply $message, $delAll) {
        if ($delAll) {
            $nodes = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $message['node_id'])->fetch();

            foreach ($nodes as $node) {
                $node->delete();
            }
        } else {
            $nodes = $this->finder('FS\ForumAutoReply:ForumAutoReply')->where('node_id', $message['node_id'])->where('prefix_id', '!=', NULL)->fetch();

            foreach ($nodes as $node) {
                $node->delete();
            }
        }
    }

    protected function pagination($start, $limit) {
        $db = \XF::db();
        $data = $db->fetchAll('SELECT node_id,MIN(message_id) as message_id, MIN(no_match_prefix_id) as no_match_prefix_id ,MIN(user_group_id) as user_group_id ,MIN(prefix_id) as prefix_id FROM fs_forum_auto_reply GROUP BY node_id ORDER BY message_id DESC LIMIT ' . (int) $start . "," . (int) $limit);

        $total = count($db->fetchAll('SELECT node_id,MIN(message_id) as message_id ,MIN(user_group_id) as user_group_id ,MIN(prefix_id) as prefix_id FROM fs_forum_auto_reply GROUP BY node_id'));

        $viewParams = [
            'data' => $data,
            'total' => $total,
            'totalReturn' => count($data),
        ];

        return $viewParams;
    }

    /**
     * @return \XF\Repository\UserGroup
     */
    protected function getUserGroupRepo() {
        return $this->repository('XF:UserGroup');
    }

    /**
     * @return \XF\Repository\Node
     */
    protected function getNodesRepo() {
        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = \XF::repository('XF:Node');
        $nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());

        return $nodeTree;
    }

    /**
     * @return \XF\Repository\ThreadPrefix
     */
    protected function getPrefixRepo() {
        /** @var \XF\Repository\ThreadPrefix $prefixRepo */
        $prefixRepo = $this->repository('XF:ThreadPrefix');
        $prefixListData = $prefixRepo->getPrefixListData();

        return $prefixListData;
    }

    protected function noMatchUserNames($userIds) {


        $users_ids = explode(", ", $userIds);

        $users_names = array();

        foreach ($users_ids as $value) {
            $user = null;
            if ($value) {
                $user = $this->em()->findOne('XF:User', ['user_id' => $value]);

                if (!$user) {
                    throw $this->exception($this->error(\XF::phraseDeferred('requested_user_x_not_found', ['name' => $value])));
                }
                array_push($users_names, $user['username'] . ', ');
            }
        }

        $users_names = implode("", $users_names);

        $viewParams = [
            'no_match_user_names' => $users_names
        ];

        return $viewParams;
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \CRUD\XF\Entity\Crud
     */
    protected function assertMessageExists($id, array $extraWith = [], $phraseKey = null) {
        return $this->assertRecordExists('FS\ForumAutoReply:ForumAutoReply', $id, $extraWith, $phraseKey);
    }

}
