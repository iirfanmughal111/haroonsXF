<?php

namespace FS\UpgradeUserGroup;

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
		$this->schemaManager()->createTable('fs_upgrade_userGroup', function (Create $table) {

			$table->addColumn('usg_id', 'int', '255')->autoIncrement();

			$table->addColumn('current_userGroup', 'int', '255')->nullable();
			$table->addColumn('message_count', 'int', '255')->nullable();
			$table->addColumn('last_login', 'int', '255')->nullable();
			$table->addColumn('upgrade_userGroup', 'int', '255')->nullable();
			$table->addPrimaryKey('usg_id');
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		$sm->dropTable('fs_upgrade_userGroup');
	}
}
