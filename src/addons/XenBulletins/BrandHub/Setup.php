<?php

namespace XenBulletins\BrandHub;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XenBulletins\BrandHub\Install\Data\MySql;

class Setup extends AbstractSetup {

    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;


   
    
    public function installStep1() 
    {
        $sm = $this->schemaManager();

        foreach ($this->getTables() AS $tableName => $closure) {
            $sm->createTable($tableName, $closure);
        }
        
        
        $sm->alterTable('xf_thread', function(Alter $table) {
             $table->addColumn('item_id', 'int')->setDefault(0);
        });
        
        $sm->alterTable('xf_forum', function(Alter $table) {
             $table->addColumn('brand_id', 'int')->setDefault(0);
        });
        
        $sm->alterTable('xf_attachment', function(Alter $table) {
             $table->addColumn('page_id', 'int')->setDefault(0);
              $table->addColumn('user_id', 'int')->setDefault(0);
              $table->addColumn('item_main_photo', 'int')->setDefault(0);
             $table->addColumn('page_main_photo', 'int')->setDefault(0);
        });
        
        
        $this->createWidget('bh_highest_rated_items', 'highestRatedInCategory', []);
    
      
    }
        
        
    protected function getTables() 
    {
        $data = new MySql();
        
        return $data->getTables();
    }
    
    
    
    public function uninstallStep1() 
    {
        $sm = $this->schemaManager();

        foreach (array_keys($this->getTables()) AS $tableName) {
            $sm->dropTable($tableName);
        }
        
        $sm->alterTable('xf_thread', function(Alter $table) {
             $table->dropColumns(['item_id']);
        });
        
        $sm->alterTable('xf_forum', function(Alter $table) {
            $table->dropColumns(['brand_id']);
        });
        
        $sm->alterTable('xf_attachment', function(Alter $table) {
              $table->dropColumns(['page_id','user_id','item_main_photo','page_main_photo']);
        });
        
        
        $this->deleteWidget('bh_highest_rated_items');
        
        $db = \XF::db();
        $sql = "DELETE FROM xf_attachment WHERE content_type='bh_item'";
        $db->query($sql);
        
        $sql = "DELETE FROM xf_bookmark_item WHERE content_type='bh_item'";
        $db->query($sql);
       
        $sql = "DELETE FROM xf_reaction_content WHERE content_type  ='bh_item'";
        $db->query($sql);
        
    }
    
      public function upgrade1010600Step1() {

        $sm = $this->schemaManager();

        $sm->alterTable('xf_attachment', function(Alter $table) {
              $table->addColumn('item_main_photo', 'int')->setDefault(0);
             $table->addColumn('page_main_photo', 'int')->setDefault(0);
        });
    }
    
    public function upgrade1010800Step1() {

        $sm = $this->schemaManager();

        $sm->alterTable('bh_item', function(Alter $table) {
                $table->addColumn('reaction_score', 'int');
                $table->addColumn('reaction_users', 'mediumblob');
                $table->addColumn('reactions', 'mediumblob');
                $table->addColumn('user_id', 'int');
        });
        
        
        $sm->alterTable('bh_item_subscribe', function(Alter $table) {
            $table->addColumn('create_date', 'int');
        });
        
    }
    
    
    
public function upgrade1020000Step1() {

        $sm = $this->schemaManager();

        $sm->alterTable('bh_owner_page', function(Alter $table) {
                $table->addColumn('reaction_score', 'int');
                $table->addColumn('reaction_users', 'mediumblob');
                $table->addColumn('reactions', 'mediumblob');
        });
        
    }
   

    
    
    

}
