<?php

namespace Demo;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;
class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;
	public function installStep1()
    {
        $this->schemaManager()->createTable('demo_pad_note', function(\XF\Db\Schema\Create $table)
    {
        $table->addColumn('note_id', 'int')->autoIncrement();
        $table->addColumn('user_id', 'int')->setDefault(0);
        $table->addColumn('title', 'varchar', 255);
        $table->addColumn('content', 'text');
        $table->addColumn('post_date', 'int')->setDefault(0);
        $table->addColumn('edit_date', 'int')->setDefault(0);
    });
    }
}