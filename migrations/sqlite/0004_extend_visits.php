<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("ALTER TABLE visits ADD country_code TEXT");
        $database->exec("ALTER TABLE visits ADD browser TEXT");
        $database->exec("ALTER TABLE visits ADD operating_system TEXT");
        $database->exec("ALTER TABLE visits ADD device_type TEXT");
    }
};