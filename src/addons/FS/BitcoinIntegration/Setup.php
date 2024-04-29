<?php

namespace FS\BitcoinIntegration;

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

		$this->schemaManager()->createTable('xf_bitcoin_purchase_record', function (Create $table) {

			$table->addColumn('id', 'int')->autoIncrement();

			$table->addColumn('user_id', 'int');
			$table->addColumn('user_upgrade_id', 'int');
			$table->addColumn('status', 'int')->setDefault(0);
			$table->addColumn('end_at', 'int')->setDefault(0);

			$table->addPrimaryKey('id');
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		$sm->dropTable('xf_bitcoin_purchase_record');
	}
}
