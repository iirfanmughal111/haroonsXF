<?php

namespace FS\CRUD\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
    public function getTables()
    {
        $tables = [];

        $tables['fs_crud'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('id', 'int')->autoIncrement();
            $table->addColumn('name', 'varchar', 20);
            $table->addColumn('class', 'text');
            $table->addColumn('rollNo', 'int');
            $table->addPrimaryKey('id');
        };

        return $tables;
    }
}
