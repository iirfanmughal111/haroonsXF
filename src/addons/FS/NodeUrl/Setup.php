<?php

namespace FS\NodeUrl;

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
		$this->schemaManager()->alterTable('xf_node', function (Alter $table) {
			$table->addColumn('node_url', 'text')->nullable();
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_node', function (Alter $table) {
			$table->dropColumns(['node_url']);
		});
	}
}
