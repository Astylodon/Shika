<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("ALTER TABLE users ADD role TINYINT NOT NULL DEFAULT 2");
    }
};