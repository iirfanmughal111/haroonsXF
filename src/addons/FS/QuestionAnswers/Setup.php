<?php

namespace FS\QuestionAnswers;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use FS\QuestionAnswers\Helper;

class Setup extends AbstractSetup
{
        use StepRunnerInstallTrait;
        use StepRunnerUpgradeTrait;
        use StepRunnerUninstallTrait;

        public function installStep1()
        {
                $sm = $this->schemaManager();

                $sm->alterTable('xf_user', function (Alter $table) {
                        $table->addColumn('question_count', 'int', 10)->setDefault(0);
                        $table->addColumn('answer_count', 'int', 10)->setDefault(0);
                });
        }

        public function uninstallStep1()
        {

                $sm = $this->schemaManager();

                $sm->alterTable('xf_user', function (Alter $table) {
                        $table->dropColumns(['question_count', 'answer_count']);
                });
        }
}
