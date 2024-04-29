<?php

namespace FS\AssignGroup\Service;

use XF\Mvc\FormAction;

class AssignGroup extends \XF\Service\AbstractService
{

    public function createUserGroup()
    {
        $userGroup = \XF::em()->create('XF:UserGroup');
        $this->userGroupSaveProcess($userGroup)->run();

        return $userGroup;
    }

    protected function userGroupSaveProcess(\XF\Entity\UserGroup $userGroup)
    {
        $form = \XF::app()->formAction();

        $input = [
            'title' => 'Basic',
            'display_style_priority' => '0',
            'username_css' => '',
            'banner_css_class' => 'userBanner userBanner--primary',
            'banner_text' => ''
        ];

        $input['user_title'] = '';

        $form->basicEntitySave($userGroup, $input);

        /** @var \XF\Service\UpdatePermissions $permissionUpdater */
        $permissionUpdater = \XF::app()->service('XF:UpdatePermissions');
        $permissions = [
            "general" =>
            [
                "view" =>
                "allow",
                "viewNode" =>
                "unset",
                "viewMemberList" =>
                "unset",
                "viewProfile" =>
                "unset",
                "usePush" =>
                "unset",
                "maxMentionedUsers" =>
                "0",
                "search" =>
                "unset",
                "createTag" =>
                "unset",
                "bypassUserTagLimit" =>
                "unset",
                "editProfile" =>
                "unset",
                "editCustomTitle" =>
                "unset",
                "requireTfa" =>
                "unset",
                "changeUsername" =>
                "unset",
                "changeUsernameNoApproval" =>
                "unset",
                "bypassFloodCheck" =>
                "unset",
                "bypassSpamCheck" =>
                "unset",
                "bypassNofollowLinks" =>
                "unset",
                "report" =>
                "unset",
                "useContactForm" =>
                "unset",
                "viewIps" =>
                "unset",
                "bypassUserPrivacy" =>
                "unset",
                "cleanSpam" =>
                "unset",
                "viewWarning" =>
                "unset",
                "warn" =>
                "unset",
                "manageWarning" =>
                "unset",
                "editBasicProfile" =>
                "unset",
                "approveRejectUser" =>
                "unset",
                "approveUsernameChange" =>
                "unset",
                "banUser" =>
                "unset",
                "editSignature" =>
                "unset",
            ], "avatar" =>
            [
                "allowed" =>
                "unset"
            ], "profileBanner" =>
            [
                "allowed" =>
                "unset"
            ], "bookmark" =>
            [
                "view" =>
                "unset",
                "create" =>
                "unset",
            ], "forum" =>
            [
                "viewOthers" =>
                "unset",
                "viewContent" =>
                "unset",
                "react" =>
                "unset",
                "contentVote" =>
                "unset",
                "markSolution" =>
                "unset",
                "postThread" =>
                "unset",
                "postReply" =>
                "unset",
                "deleteOwnPost" =>
                "unset",
                "editOwnPost" =>
                "unset",
                "editOwnPostTimeLimit" =>
                "0",
                "editOwnThreadTitle" =>
                "unset",
                "deleteOwnThread" =>
                "unset",
                "viewAttachment" =>
                "unset",
                "uploadAttachment" =>
                "unset",
                "uploadVideo" =>
                "unset",
                "tagOwnThread" =>
                "unset",
                "tagAnyThread" =>
                "unset",
                "manageOthersTagsOwnThread" =>
                "unset",
                "votePoll" =>
                "unset",
                "inlineMod" =>
                "unset",
                "stickUnstickThread" =>
                "unset",
                "lockUnlockThread" =>
                "unset",
                "manageAnyThread" =>
                "unset",
                "deleteAnyThread" =>
                "unset",
                "hardDeleteAnyThread" =>
                "unset",
                "threadReplyBan" =>
                "unset",
                "editAnyPost" =>
                "unset",
                "deleteAnyPost" =>
                "unset",
                "hardDeleteAnyPost" =>
                "unset",
                "warn" =>
                "unset",
                "manageAnyTag" =>
                "unset",
                "viewDeleted" =>
                "unset",
                "viewModerated" =>
                "unset",
                "undelete" =>
                "unset",
                "approveUnapprove" =>
                "unset",
                "markSolutionAnyThread" =>
                "unset",
            ], "conversation" =>
            [
                "start" =>
                "unset",
                "receive" =>
                "unset",
                "react" =>
                "unset",
                "uploadAttachment" =>
                "unset",
                "uploadVideo" =>
                "unset",
                "editOwnMessage" =>
                "unset",
                "editOwnMessageTimeLimit" =>
                "0",
                "maxRecipients" =>
                "0",
                "editAnyMessage" =>
                "unset",
                "alwaysInvite" =>
                "unset",
            ],
            "signature" =>
            [
                "basicText" =>
                "unset",
                "extendedText" =>
                "unset",
                "align" =>
                "unset",
                "list" =>
                "unset",
                "image" =>
                "unset",
                "link" =>
                "unset",
                "media" =>
                "unset",
                "block" =>
                "unset",
                "maxPrintable" =>
                "0",
                "maxLines" =>
                "0",
                "maxLinks" =>
                "0",
                "maxImages" =>
                "0",
                "maxSmilies" =>
                "0",
                "maxTextSize" =>
                "0",
            ], "profilePost" =>
            [
                "view" =>
                "unset",
                "react" =>
                "unset",
                "manageOwn" =>
                "unset",
                "post" =>
                "unset",
                "comment" =>
                "unset",
                "deleteOwn" =>
                "unset",
                "editOwn" =>
                "unset",
                "viewAttachment" =>
                "unset",
                "uploadAttachment" =>
                "unset",
                "uploadVideo" =>
                "unset",
                "inlineMod" =>
                "unset",
                "editAny" =>
                "unset",
                "deleteAny" =>
                "unset",
                "hardDeleteAny" =>
                "unset",
                "warn" =>
                "unset",
                "viewDeleted" =>
                "unset",
                "viewModerated" =>
                "unset",
                "undelete" =>
                "unset",
                "approveUnapprove" =>
                "unset",
            ],
            "resource" =>
            [
                "view" =>
                "unset",
                "viewUpdateAttach" =>
                "unset",
                "download" =>
                "unset",
                "react" =>
                "unset",
                "rate" =>
                "unset",
                "contentVote" =>
                "unset",
                "add" =>
                "unset",
                "uploadUpdateAttach" =>
                "unset",
                "uploadUpdateVideo" =>
                "unset",
                "updateOwn" =>
                "unset",
                "tagOwnResource" =>
                "unset",
                "tagAnyResource" =>
                "unset",
                "manageOthersTagsOwnRes" =>
                "unset",
                "reviewReply" =>
                "unset",
                "deleteOwn" =>
                "unset",
                "manageOwnTeam" =>
                "unset",
                "inlineMod" =>
                "unset",
                "viewDeleted" =>
                "unset",
                "deleteAny" =>
                "unset",
                "undelete" =>
                "unset",
                "hardDeleteAny" =>
                "unset",
                "deleteAnyReview" =>
                "unset",
                "editAny" =>
                "unset",
                "reassign" =>
                "unset",
                "manageAnyTag" =>
                "unset",
                "viewModerated" =>
                "unset",
                "approveUnapprove" =>
                "unset",
                "featureUnfeature" =>
                "unset",
                "warn" =>
                "unset",
            ]
        ];

        $form->apply(function () use ($userGroup, $permissions, $permissionUpdater) {
            $permissionUpdater->setUserGroup($userGroup)->setGlobal();
            $permissionUpdater->updatePermissions($permissions);
        });

        return $form;
    }

    public function updateOptionsGroup($groupId)
    {
        $optionIndex = \xf::app()->finder('XF:Option')->where('option_id', 'fs_assign_group_applicable')->fetchOne();

        if ($optionIndex) {
            $optionIndex->option_value = $groupId;
            $optionIndex->save();
        }
    }
}
