<?php

namespace FS\Limitations;

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

        $this->schemaManager()->createTable('fs_limitations_user_groups', function (Create $table) {

            $table->addColumn('id', 'int')->autoIncrement();

            $table->addColumn('user_group_id', 'int')->setDefault(0);
            $table->addColumn('node_ids', 'text')->nullable();
            $table->addColumn('daily_ads', 'int')->setDefault(0);
            $table->addColumn('daily_repost', 'int')->setDefault(0);

            $table->addPrimaryKey('id');
        });

        $sm->alterTable('xf_user', function (Alter $table) {
            $table->addColumn('daily_discussion_count', 'smallint', 5)->setDefault(0);
            $table->addColumn('conversation_message_count', 'int')->setDefault(0);
            $table->addColumn('daily_ads', 'int')->setDefault(0);
            $table->addColumn('daily_repost', 'int')->setDefault(0);
            $table->addColumn('last_repost', 'int')->setDefault(0);
            // $table->addColumn('media_storage_size', 'bigint')->setDefault(0)->comment('size in bytes');
        });
    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        $sm->dropTable('fs_limitations_user_groups');

        $sm->alterTable('xf_user', function (Alter $table) {
            $columns = [
                'daily_discussion_count',
                'conversation_message_count',
                'daily_ads',
                'daily_repost',
                'last_repost',
                // 'media_storage_size'
            ];

            $table->dropColumns($columns);
        });
    }
}
