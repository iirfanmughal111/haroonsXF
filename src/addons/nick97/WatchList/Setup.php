<?php

namespace nick97\WatchList;

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

		$this->db()->insertBulk('xf_connected_account_provider', [
			[
				'provider_id' => 'nick_trakt',
				'provider_class' => 'nick97\\WatchList:Provider\\Trakt',
				'display_order' => 170,
				'options' => '[]'
			]
		], 'provider_id');

		$this->schemaManager()->alterTable(
			'xf_user_privacy',
			function (Alter $table) {
				$table->addColumn(
					'allow_view_watchlist',
					'ENUM',
					['everyone', 'members', 'followed', 'none']
				)->setDefault('everyone');
				$table->addColumn(
					'allow_view_stats',
					'ENUM',
					['everyone', 'members', 'followed', 'none']
				)->setDefault('everyone');
			}
		);

		$this->schemaManager()->createTable('xf_watch_list_all', function (Create $table) {

			$table->addColumn('id', 'int')->autoIncrement();

			$table->addColumn('user_id', 'int');
			$table->addColumn('thread_id', 'int');
			$table->addColumn('created_at', 'int')->setDefault(0);

			$table->addPrimaryKey('id');
		});
	}

	public function uninstallStep1()
	{
		$this->db()->delete('xf_connected_account_provider', "provider_id = 'nick_trakt'");

		$this->schemaManager()->alterTable(
			'xf_user_privacy',
			function (Alter $table) {
				$table->dropColumns([
					'allow_view_watchlist',
					'allow_view_stats',
				]);
			}
		);

		$sm = $this->schemaManager();

		$sm->dropTable('xf_watch_list_all');
	}

	// ################################## UPGRADE ###########################################

	public function upgrade1000800Step1()
	{
		$sm = $this->schemaManager();
		$sm->renameTable('nick97_watch_list_all', 'xf_watch_list_all');
	}
}
