<?php

namespace XC\ThreadGPTBot;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XC\ThreadGPTBot\Install\Data\MySql;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1() {
        $sm = $this->schemaManager();

        foreach ($this->getTables() AS $tableName => $closure) {
            $sm->createTable($tableName, $closure);
        }
    }

    protected function getTables() {
        $data = new MySql();

        return $data->getTables();
    }

    public function uninstallStep1() {
        $sm = $this->schemaManager();

        foreach (array_keys($this->getTables()) AS $tableName) {
            $sm->dropTable($tableName);
        }
    }

}
