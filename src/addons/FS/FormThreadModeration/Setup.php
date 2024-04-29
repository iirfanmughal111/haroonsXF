<?php

namespace FS\FormThreadModeration;

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

	public function installStep1()
	{
		$this->schemaManager()->alterTable('xf_thread', function (Alter $table) {
			$table->addColumn('parent_thread_id', 'int')->nullable();
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_thread', function (Alter $table) {
			$table->dropColumns(['parent_thread_id']);
		});
	}
}
