<?php

namespace FS\BunnyIntegration\Install\Data;

use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class MySql
{
    public function getTables()
    {
        $tables = [];

        $tables['fs_bunny_thread_videos'] = function (Create $table) {
            /** @var Create|Alter $table */
            $table->addColumn('req_id', 'int')->autoIncrement();
            $table->addColumn('thread_id', 'int')->setDefault(0);
            $table->addColumn('bunny_library_id', 'int')->setDefault(0);
            $table->addColumn('bunny_video_id', 'mediumtext')->nullable()->setDefault(null);
            $table->addPrimaryKey('req_id');
        };

        return $tables;
    }
}
