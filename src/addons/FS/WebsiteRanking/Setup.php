<?php

namespace FS\WebsiteRanking;

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

                $sm->alterTable('xf_thread', function (Alter $table) {
                        $table->addColumn('issue_status', 'int')->setDefault(0);
                        $table->addColumn('is_complain', 'int')->setDefault(0)->comment('is Complain Thread on websiteRanking');
                });


                $sm->alterTable('xf_node', function (Alter $table) {

                        $table->addColumn('issue_count', 'int')->setDefault(0)->comment('Total number of issues');

                        $table->addColumn('solved_count', 'int')->setDefault(0);
                        $table->addColumn('unsolved_count', 'int')->setDefault(0);
                        $table->addColumn('pending_count', 'int')->setDefault(0);

                        $table->addColumn('solved_percentage', 'float')->unsigned(false)->nullable()->comment('Solved issues percentage');
                        $table->addColumn('unsolved_percentage', 'float')->unsigned(false)->nullable()->comment('Unsolved issues percentage');
                        $table->addColumn('pending_percentage', 'float')->unsigned(false)->nullable()->comment('Pending issues percentage');
                });
        }

        public function postInstall(array &$stateChanges)
        {
                $websiteService = \xf::app()->service('FS\WebsiteRanking:CreateParentWebsite');

                $node = $websiteService->createWebsite();
                $websiteService->updateWebsiteOption($node->node_id);
                $websiteService->permissionRebuild();
        }

        public function uninstallStep1()
        {
                $sm = $this->schemaManager();

                $sm->alterTable('xf_thread', function (Alter $table) {

                        $table->dropColumns(['issue_status']);
                });

                $sm->alterTable('xf_node', function (Alter $table) {

                        $table->dropColumns([
                                'issue_count',
                                'pending_count',
                                'solved_count',
                                'unsolved_count',
                                'solved_percentage',
                                'unsolved_percentage',
                                'pending_percentage'
                        ]);
                });

                $forum = \xf::app()->finder('XF:Node')->whereId($this->app()->options()->fs_web_ranking_parent_web_id)->fetchOne();

                if ($forum) {
                        $forum->delete();
                }
        }
}
