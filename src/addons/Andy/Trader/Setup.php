<?php

namespace Andy\Trader;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
	use StepRunnerUpgradeTrait;
	
	public function install(array $stepParams = [])
	{
		$this->schemaManager()->createTable('xf_andy_trader', function(Create $table)
		{
			$table->addColumn('trader_id', 'int')->autoIncrement();
			$table->addColumn('timestamp', 'int');
			$table->addColumn('rating', 'int');
			$table->addColumn('seller_id', 'int');
			$table->addColumn('buyer_id', 'int');
			$table->addColumn('seller_comment', 'text');
			$table->addColumn('buyer_comment', 'text');
		});
		
		$this->query(
			"ALTER TABLE xf_user
				ADD andy_trader_seller_count INT(10) UNSIGNED NOT NULL DEFAULT 0
		");
		
		$this->query(
			"ALTER TABLE xf_user
				ADD andy_trader_buyer_count INT(10) UNSIGNED NOT NULL DEFAULT 0
		");
	}

	public function upgrade(array $stepParams = [])
	{
	}

	public function uninstall(array $stepParams = [])
	{
		$this->query("
			DROP TABLE xf_andy_trader
		");
		
		$this->query(
			"ALTER TABLE xf_user
				DROP andy_trader_seller_count
		");
		
		$this->query(
			"ALTER TABLE xf_user
				DROP andy_trader_buyer_count
		");
	}
}