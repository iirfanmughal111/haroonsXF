<?php

namespace FS\AutoForumManager;

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
		$this->schemaManager()->createTable('fs_auto_forum_manage', function (Create $table) {

			$table->addColumn('forum_manage_id', 'int', '255')->autoIncrement();

			$table->addColumn('admin_id', 'int', '255')->nullable();
			$table->addColumn('last_login_days', 'int', '255')->nullable();
			$table->addColumn('node_id', 'int', '255')->nullable();
			$table->addPrimaryKey('forum_manage_id');
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();
		$sm->dropTable('fs_auto_forum_manage');
	}
}
