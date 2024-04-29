<?php

namespace BS\ScheduledPosting;

use BS\ScheduledPosting\Install\Upgrade1000073;
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
	use Upgrade1000073;

    // ################################ INSTALLATION ######################

    public function installStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() AS $tableName => $closure)
        {
            $sm->createTable($tableName, $closure);
        }
    }

    public function installStep2()
    {
        $sm = $this->schemaManager();

        foreach ($this->getAlterTables() AS $tableName => $closure)
        {
            $sm->alterTable($tableName, $closure[0]);
        }
    }

    // ############################### UPGRADE 1.0.0c ###########################

    public function upgrade1000073Step1(array $stepParams)
    {
        $position = empty($stepParams[0]) ? 0 : $stepParams[0];

        return $this->fixOldestScheduledThreads($position, $stepParams);
    }

    // ############################### UNINSTALL ###########################

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        foreach (array_keys($this->getTables()) AS $tableName)
        {
            $sm->dropTable($tableName);
        }
    }

    public function uninstallStep2()
    {
        $db = $this->db();

        $db->update('xf_thread', ['discussion_state' => 'visible'], 'discussion_state = \'scheduled\'');
        $db->update('xf_post', ['message_state' => 'visible'], 'message_state = \'scheduled\'');
        $db->update('xf_profile_post', ['message_state' => 'visible'], 'message_state = \'scheduled\'');
    }

    public function uninstallStep3()
    {
        $sm = $this->schemaManager();

        foreach ($this->getAlterTables() AS $tableName => $closure)
        {
            $sm->alterTable($tableName, $closure[1]);
        }
    }

    // ############################# TABLE / DATA DEFINITIONS ##############################

    protected function getTables()
    {
        $tables = [];

        $tables['xf_bs_schedule'] = function(Create $table)
        {
            $table->addColumn('schedule_id', 'int')->autoIncrement();
            $table->addColumn('content_type', 'varbinary', 25);
            $table->addColumn('content_id', 'int');
            $table->addColumn('content_user_id', 'int');
            $table->addColumn('schedule_date', 'int')->setDefault(0);
            $table->addColumn('posting_date', 'int')->setDefault(0);
            $table->addUniqueKey(['content_type', 'content_id']);
            $table->addKey('posting_date');
        };

        return $tables;
    }

    protected function getAlterTables()
    {
        $tables = [];

        $tables['xf_thread'] =
        [
            function(Alter $table)
            {
                $table->changeColumn('discussion_state')->addValues('scheduled');
            },
            function(Alter $table)
            {
                $table->changeColumn('discussion_state')->removeValues('scheduled');
            }
        ];

        $tables['xf_post'] =
        [
            function(Alter $table)
            {
                $table->changeColumn('message_state')->addValues('scheduled');
            },
            function(Alter $table)
            {
                $table->changeColumn('message_state')->removeValues('scheduled');
            }
        ];

        $tables['xf_profile_post'] =
        [
            function(Alter $table)
            {
                $table->changeColumn('message_state')->addValues('scheduled');
            },
            function(Alter $table)
            {
                $table->changeColumn('message_state')->removeValues('scheduled');
            }
        ];

        return $tables;
    }
}