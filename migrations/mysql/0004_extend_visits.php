<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("ALTER TABLE visits ADD country_code VARCHAR(2)");
        $database->exec("ALTER TABLE visits ADD browser VARCHAR(50)");
        $database->exec("ALTER TABLE visits ADD operating_system VARCHAR(50)");
        $database->exec("ALTER TABLE visits ADD device_type VARCHAR(8)");
    }
};