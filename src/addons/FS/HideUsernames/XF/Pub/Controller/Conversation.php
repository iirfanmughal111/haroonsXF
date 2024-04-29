<?php

namespace FS\HideUsernames\XF\Pub\Controller;


class Conversation extends XFCP_Conversation
{

    public function actionAdd()
    {
        if (!$this->isPost()) {

            $to = $this->filter('to', 'str');

            if ($to !== '' && strpos($to, ',') === false) {
                /** @var \XF\Entity\User $toUser */
                $toUser = $this->em()->findOne('XF:User', ['username' => $to]);
                if ($toUser) {
                    return parent::actionAdd();
                }
            }

            $visitor = \XF::visitor();

            if (!$visitor->canStartConversation()) {
                return $this->noPermission();
            }

            $to = $this->filter('to', 'str');
            $title = $this->filter('title', 'str');
            $message = $this->filter('message', 'str');

            if ($to !== '' && strpos($to, ',') === false) {
                /** @var \XF\Entity\User $toUser */
                $toUser = $this->em()->findOne('XF:User', ['random_name' => $to]);
                if (!$toUser) {
                    return $this->notFound(\XF::phrase('requested_user_not_found'));
                }

                if ($visitor->user_id == $toUser->user_id) {
                    return $this->noPermission(\XF::phrase('you_may_not_start_conversation_with_yourself'));
                }

                if (!$visitor->canStartConversationWith($toUser)) {
                    return $this->noPermission(\XF::phrase('you_may_not_start_conversation_with_x_because_of_their_privacy_settings', ['name' => $toUser->username]));
                }
            }

            /** @var \XF\Entity\ConversationMaster $conversation */
            $conversation = $this->em()->create('XF:ConversationMaster');

            $draft = \XF\Draft::createFromKey('conversation');

            if ($conversation->canUploadAndManageAttachments()) {
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $attachmentData = $attachmentRepo->getEditorData('conversation_message', null, $draft->attachment_hash);
            } else {
                $attachmentData = null;
            }

            $viewParams = [
                'to' => $to ?: $draft->recipients,
                'title' => $title ?: $draft->title,
                'message' => $message ?: $draft->message,

                'conversation' => $conversation,
                'maxRecipients' => $conversation->getMaximumAllowedRecipients(),
                'draft' => $draft,

                'attachmentData' => $attachmentData
            ];
            return $this->view('XF:Conversation\Add', 'conversation_add', $viewParams);
        }
        return parent::actionAdd();
    }
}
