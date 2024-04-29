<?php

namespace FS\BunnyIntegration;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use FS\BunnyIntegration\Install\Data\MySql;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installstep1()
	{

		$sm = $this->schemaManager();

		foreach ($this->getTables() as $tableName => $callback) {
			$sm->createTable($tableName, $callback);
		}

		$this->alterTable('xf_attachment', function (\XF\Db\Schema\Alter $table) {
			$table->addColumn('bunny_vid_id', 'mediumtext')->nullable()->setDefault(null);
			$table->addColumn('is_bunny', 'int')->setDefault(0);
		});
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName) {
			$sm->dropTable($tableName);
		}

		$this->schemaManager()->alterTable('xf_attachment', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['bunny_vid_id']);
			$table->dropColumns(['is_bunny']);
		});
	}

	protected function getTables()
	{
		$data = new MySql();
		return $data->getTables();
	}
}
