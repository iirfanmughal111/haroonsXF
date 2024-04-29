<?php

namespace CRUD;

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
        $this->schemaManager()->createTable('xf_crud', function (Create $table) {
            $table->addColumn('id', 'int')->autoIncrement();
            $table->addColumn('name', 'varchar', 20);
            $table->addColumn('class', 'text');
            $table->addColumn('rollNo', 'int');
            // $table->addColumn('rollNo', 'int')->setDefault(0);
        });

    }


    public function uninstallStep1()
    {
    }
}


//                      uper schema create krney k baad cmd open kr k xenforo ko target kr k ye command run krni hai uder jo nichey likhi hai

// 					php cmd.php xf-addon:install-step CRUD 1