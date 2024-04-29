<?php

namespace FS\AssignGroup;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installstep1()
	{
		$sm = $this->schemaManager();
	}

	public function postInstall(array &$stateChanges)
	{
		$userGroupService = \xf::app()->service('FS\AssignGroup:AssignGroup');

		$userGroup = $userGroupService->createUserGroup();
		$userGroupService->updateOptionsGroup($userGroup->user_group_id);
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
	}
}
