<?php

namespace XenBulletins\GlobalThreads;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1() {
        $this->schemaManager()->alterTable(
                'xf_node', function (Alter $table) {
            $table->addColumn('g_thread_ids', 'varbinary', 255)->nullable();
        }
        );
    }

    public function uninstallStep1() {
        $this->schemaManager()->alterTable(
                'xf_node', function (Alter $table) {
            $table->dropColumns([
                'g_thread_ids'
            ]);
        }
        );
    }

}
