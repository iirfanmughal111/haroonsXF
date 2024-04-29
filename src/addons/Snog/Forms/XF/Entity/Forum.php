<?php

namespace Snog\Forms\XF\Entity;

class Forum extends XFCP_Forum
{
	public function canCreateThreadFromForm($user, &$error = null)
	{
		if (!$this->allow_posting)
		{
			$error = \XF::phraseDeferred('you_may_not_perform_this_action_because_forum_does_not_allow_posting');

			return false;
		}

		return $user->hasNodePermission($this->node_id, 'postThread');
	}

	public function canUploadAndManageAttachmentsFromForm($user)
	{
		$value = $user->hasNodePermission($this->node_id, 'uploadAttachment');

		if ($value)
		{
			$value = $user->hasNodePermission($this->node_id, 'viewAttachment');
		}

		return $value;
	}
}