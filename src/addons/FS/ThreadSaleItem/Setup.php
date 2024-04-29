<?php

namespace FS\ThreadSaleItem;

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

    public function installStep1() {


        $sm = $this->schemaManager();

         $sm->alterTable('xf_thread', function (Alter $table) {
        
            $table->addColumn('sale_item', 'int')->setDefault(0);
        });
       		
    }

    public function uninstallStep1() {
       
        $sm = $this->schemaManager();

        
        $sm->alterTable('xf_thread', function (Alter $table) {
            $table->dropColumns(['sale_item']);
        });
        
    }

  

}
