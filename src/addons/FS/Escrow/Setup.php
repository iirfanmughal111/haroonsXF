<?php

namespace FS\Escrow;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

use FS\Escrow\Install\Data\MySql;


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

		$this->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {

			$table->addColumn('deposit_amount', 'decimal', '10,2');
		});
		$this->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {

			$table->addColumn('escrow_id', 'int')->setDefault(0);
		});

		$forumService = \xf::app()->service('FS\Escrow:Escrow\createForum');

		$node = $forumService->createNode();
		$forumService->updateOptionsforum($node->node_id);
		$forumService->permissionRebuild();
	}

	public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		foreach (array_keys($this->getTables()) as $tableName) {
			$sm->dropTable($tableName);
		}

		$this->schemaManager()->alterTable('xf_user', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['deposit_amount']);
		});
		$this->schemaManager()->alterTable('xf_thread', function (\XF\Db\Schema\Alter $table) {
			$table->dropColumns(['escrow_id']);
		});

		$forum = \xf::app()->finder('XF:Node')->whereId(intval($this->app()->options()->fs_escrow_applicable_forum))->fetchOne();

		if ($forum) {
			$forum->delete();
		}
	}

	protected function getTables()
	{
		$data = new MySql();
		return $data->getTables();
	}
}
