<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("
            CREATE TABLE site_allowed_hosts (
                site_id         INT(11),
                allowed_host    VARCHAR(255),
                PRIMARY KEY (site_id, allowed_host)
            )
        ");
    }
};