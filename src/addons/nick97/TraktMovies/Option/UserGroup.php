<?php

namespace nick97\TraktMovies\Option;

use XF\Option\AbstractOption;

class UserGroup extends AbstractOption
{
	public static function renderCheckboxes(\XF\Entity\Option $option, array $htmlParams)
	{
		/** @var \XF\Repository\UserGroup $userGroupRepo */
		$userGroupRepo = \XF::repository('XF:UserGroup');

		/** @var \XF\Entity\UserGroup[] $userGroups */
		$userGroups = $userGroupRepo->findUserGroupsForList()->fetch();
		$choices = [];

		foreach ($userGroups as $group) {
			$choices[$group->user_group_id] = $group->title;
		}

		return self::getCheckboxRow($option, $htmlParams, $choices);
	}
}
