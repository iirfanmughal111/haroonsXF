<?php

namespace FS\ApproveThreadPost;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installstep1()
	{
		$db = \XF::db();
		$db->delete('xf_permission', "permission_id = 'submitWithoutApproval'");
	}

	public function uninstallStep1()
	{
		$db = \XF::db();
		$db->insert('xf_permission', [
			'permission_id' => 'submitWithoutApproval',
			'permission_group_id' => 'general',
			'permission_type' => 'flag',
			'interface_group_id' => 'generalPermissions',
			'depend_permission_id' => '',
			'display_order' => '9000',
			'addon_id' => 'XF',
		]);
	}
        
       
}
