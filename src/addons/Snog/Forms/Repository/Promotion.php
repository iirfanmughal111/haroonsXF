<?php


namespace Snog\Forms\Repository;


use XF\Mvc\Entity\Repository;

class Promotion extends Repository
{
	public function applyPrimaryGroupChange($userId, $newGroup)
	{
		/** @var \XF\Entity\User $user */
		$user = $this->app()->em()->find('XF:User', $userId);

		if (!$user)
		{
			throw new \LogicException("User '$userId' could not be found");
		}

		$user->user_group_id = $newGroup;
		if ($user->isChanged('user_group_id'))
		{
			return $user->save(false, false);
		}

		return true;
	}

	public function promoteModerator(\Snog\Forms\Entity\Promotion $promotion, \XF\Entity\User $user)
	{
		if ($promotion->Form->make_moderator == 2)
		{
			$content_type = 'node';
			$content_id = $promotion->forum_node;
		}
		else
		{
			$content_type = '_super';
			$content_id = '';
		}

		$moderatorValues = ['user_id' => $user->user_id, 'content_type' => $content_type, 'content_id' => $content_id];

		$app = $this->app();
		$generalModerator = $app->em()->find('XF:Moderator', $user->user_id);
		if (!$generalModerator)
		{
			/** @var \XF\Entity\Moderator $generalModerator */
			$generalModerator = $app->em()->create('XF:Moderator');
			$generalModerator->user_id = $user->user_id;
		}

		$contentModerator = null;
		if ($moderatorValues['content_type'] && $moderatorValues['content_id'])
		{
			/** @var \XF\Entity\ModeratorContent $contentModerator */
			$contentModerator = $this->finder('XF:ModeratorContent')->where($moderatorValues)->fetchOne();
			if (!$contentModerator)
			{
				/** @var \XF\Entity\ModeratorContent $contentModerator */
				$contentModerator = $app->em()->create('XF:ModeratorContent');
				$contentModerator->bulkSet($moderatorValues);
			}
		}

		if (!$contentModerator)
		{
			$generalModerator->is_super_moderator = true;
		}

		if ($promotion->Form->make_moderator == 2)
		{
			$permissions = $promotion->Form->forummod;
			$moderatorSettings['globalPermissions'] = $permissions['globalPermissions'];
			$moderatorSettings['contentPermissions'] = $permissions['contentPermissions'];
			$moderatorSettings['extra_user_group_ids'] = $promotion->new_additional;
			$moderatorSettings['is_staff'] = $permissions['displaystaff'];
		}
		else
		{
			$permissions = $promotion->Form->supermod;
			$moderatorSettings['globalPermissions'] = $permissions['globalPermissions'];
			$moderatorSettings['extra_user_group_ids'] = $promotion->new_additional;
			$moderatorSettings['is_staff'] = $permissions['displaystaff'];
		}

		$this->makeModerator($generalModerator, $contentModerator, $moderatorSettings)->run();
	}

	private function makeModerator(
		\XF\Entity\Moderator $generalModerator,
		\XF\Entity\ModeratorContent $contentModerator = null,
		$moderatorSettings = []
	)
	{
		$app = $this->app();
		$form = $app->formAction();
		$moderatorUser = $generalModerator->User;
		$form->basicEntitySave($moderatorUser, ['is_staff' => $moderatorSettings['is_staff']]);

		/** @var \XF\Service\UpdatePermissions $permissionUpdater */
		$permissionUpdater = $app->service('XF:UpdatePermissions');
		$permissionUpdater->setUser($moderatorUser);
		$form->basicEntitySave($generalModerator, ['extra_user_group_ids' => $moderatorSettings['extra_user_group_ids']]);

		$form->apply(function () use ($permissionUpdater, $moderatorSettings) {
			$permissionUpdater->setGlobal();
			$permissionUpdater->updatePermissions($moderatorSettings['globalPermissions']);
		});

		if ($contentModerator)
		{
			$form->basicEntitySave($contentModerator, []);
			$form->complete(function () use ($permissionUpdater, $contentModerator, $moderatorSettings) {
				$permissionUpdater->setContent($contentModerator->content_type, $contentModerator->content_id);
				$permissionUpdater->updatePermissions($moderatorSettings['contentPermissions']);
			});
		}

		return $form;
	}

	public function closePoll(\Snog\Forms\Entity\Promotion $promotion)
	{
		/** @var \XF\Entity\Poll $poll */
		$poll = $this->app()->em()->find('XF:Poll', $promotion->poll_id);
		$poll->close_date = time() - 5;

		if ($poll->isChanged('close_date'))
		{
			return $poll->save(false, false);
		}

		return false;
	}
}