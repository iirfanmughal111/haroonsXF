<?php

namespace Awedo\PostAreas;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;

class Setup extends AbstractSetup
{
    
    const PA_TABLE_NAME = 'xf_awedo_post_areas';

    public function install (array $stepParams = [])
    {
        $this->_createCacheTable();
    }

    public function upgrade (array $stepParams = [])
    {
        $this->_createCacheTable();  // previous version 2.0.0 did not have a cache 
    }

    public function uninstall (array $stepParams = [])
    {
        $this->schemaManager()->dropTable(self::PA_TABLE_NAME);
    }
    
    protected function _createCacheTable ()            
    {
        $this->schemaManager()->createTable(self::PA_TABLE_NAME, function (Create $table)
            {
                $table->addColumn('user_id', 'mediumint');
                $table->addColumn('node_id', 'smallint');
                $table->addColumn('post_count', 'smallint');
                $table->addColumn('thread_count', 'smallint');
                $table->addPrimaryKey(['user_id', 'node_id']);
            }                                
        );        
    }
}