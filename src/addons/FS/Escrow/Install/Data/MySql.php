<?php

namespace FS\Escrow\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{

    public function getTables()
    {
        $tables = [];

        $tables['fs_escrow_transaction'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('transaction_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('to_user', 'int')->setDefault(0);
            $table->addColumn('escrow_id', 'int')->setDefault(0);
            // $table->addColumn('transaction_amount', 'int')->setDefault(0);
            $table->addColumn('transaction_amount', 'decimal', '10,2');
            $table->addColumn('transaction_type', 'varchar', 100);
            // $table->addColumn('current_amount', 'int')->setDefault(0);
            $table->addColumn('current_amount', 'decimal', '10,2');
            $table->addColumn('created_at', 'int')->setDefault(0);
            $table->addPrimaryKey('transaction_id');
        };

        $tables['fs_escrow'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('escrow_id', 'int')->autoIncrement();
            $table->addColumn('user_id', 'int')->setDefault(0);
            $table->addColumn('to_user', 'int')->setDefault(0);
            $table->addColumn('escrow_amount', 'int')->setDefault(0);
            $table->addColumn('thread_id', 'int')->setDefault(0);
            $table->addColumn('transaction_id', 'int')->setDefault(0);
            $table->addColumn('escrow_status', 'int')->setDefault(0);
            $table->addColumn('admin_percentage', 'int')->setDefault(0);
            $table->addColumn('last_update', 'int')->setDefault(0);
            $table->addPrimaryKey('escrow_id');
        };

        return $tables;
    }
}
