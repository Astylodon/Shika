<?php

namespace Shika\Database\Migrations;

use Shika\Database\Database;

interface Migration
{
    public function up(Database $database);
}