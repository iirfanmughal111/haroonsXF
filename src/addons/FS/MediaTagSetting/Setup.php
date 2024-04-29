<?php

namespace FS\MediaTagSetting;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    
    public function installStep1() 
    {
        $sm = $this->schemaManager();
        
        $sm->createTable('fs_media_tag', function (Create $table) {

            $table->addColumn('id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar',100)->setDefault('');
            $table->addColumn('media_site_ids', 'mediumblob'); 
            $table->addColumn('user_group_ids', 'mediumblob'); 
            $table->addColumn('custom_message', 'varchar',500)->setDefault('');
            $table->addColumn('create_date', 'int');
            $table->addPrimaryKey('id');
        });
    }
    
    public function uninstallStep1() 
    {
        $sm = $this->schemaManager();
        
        $sm->dropTable('fs_media_tag');       
    }
}
