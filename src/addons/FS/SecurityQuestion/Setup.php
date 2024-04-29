<?php

namespace FS\SecurityQuestion;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;
    public function installStep1() {
        $this->schemaManager()->createTable('fs_login_security_question', function (Create $table) {
            $table->addColumn('question_id', 'int')->autoIncrement();
            $table->addColumn('security_question', 'mediumtext')->nullable();
        });

        $this->schemaManager()->createTable('fs_security_question_answer', function (Create $table) {
            $table->addColumn('id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->nullable();
            $table->addColumn('question_id', 'int')->nullable();
            $table->addColumn('answer', 'mediumtext')->nullable();
        });
    }

    public function uninstallStep1() {
        $sm = $this->schemaManager();
        $sm->dropTable('fs_login_security_question');
        $sm->dropTable('fs_security_question_answer');
    }

}
