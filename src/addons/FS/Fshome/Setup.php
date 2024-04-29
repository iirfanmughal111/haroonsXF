<?php

namespace FS\Fshome;

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

    public function installstep1() {
        
    }

    public function uninstallStep1() {
        $app = \xf::app();

        $optionIndex = $app->finder('XF:Option')->where('option_id', 'indexRoute')->fetchOne();

        $optionIndex->option_value = 'forums/';
        $optionIndex->save();
    }

}
