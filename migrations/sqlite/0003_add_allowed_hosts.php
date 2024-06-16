<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("
            CREATE TABLE site_allowed_hosts (
                site_id         INTEGER,
                allowed_host    TEXT,
                PRIMARY KEY (site_id, allowed_host)
            )
        ");
    }
};