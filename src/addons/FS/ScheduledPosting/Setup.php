<?php

namespace FS\ScheduledPosting;

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
        $this->schemaManager()->createTable('fs_schedule_posting', function (Create $table) {
           
            $table->addColumn('sch_id', 'int', '255')->autoIncrement();

            $table->addColumn('title', 'mediumtext')->nullable();
            $table->addColumn('schedule_start', 'int', '255')->nullable();
            $table->addColumn('schedule_end', 'int', '255')->nullable();
            $table->addColumn('posting_start', 'int', '255')->nullable();
            $table->addColumn('msg_ex', 'varchar', '255')->nullable();
            $table->addColumn('location_ex', 'varchar', '255')->nullable();
            $table->addColumn('entries_per_two_min', 'mediumtext')->nullable();
            $table->addColumn('entries_left', 'mediumtext')->nullable();
            $table->addColumn('entry_last_id', 'mediumtext')->nullable();
            $table->addPrimaryKey('sch_id');
        });
    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();
        $sm->dropTable('fs_schedule_posting');
    }
}