<?php

namespace XenBulletin\VideoByRon;

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
        
        Public function installStep1()
	{
		$this->schemaManager()->createTable('xb_videos_by_ron', function(Create $table)
		{
			$table->addColumn('ron_id', 'int')->autoIncrement();
			$table->addColumn('title','varchar', '255');
                        $table->addColumn('video_url','varchar', '500');
                        $table->addColumn('video_id','varchar', '255');
                        $table->addColumn('date','INT', 10);
			$table->addPrimaryKey('ron_id');
		});
		
	}

        public function uninstallStep1()
	{
		$sm = $this->schemaManager();

		$sm->dropTable('xb_videos_by_ron');
	}
        
}