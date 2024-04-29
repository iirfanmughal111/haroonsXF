<?php

namespace XC\ThreadGPTBot\Install\Data;

use XF\Db\Schema\Create;

class MySql {

    public function getTables() {
       
        $tables = [];

 
        $tables['thread_bot_options'] = function (Create $table) {
            $table->addColumn('id', 'int')->autoIncrement();
            $table->addColumn('title','mediumtext');
            $table->addColumn('bot_instruction','mediumtext');
            $table->addPrimaryKey('id');
        };

       

        return $tables;
    }

}
