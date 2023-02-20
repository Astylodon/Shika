<?php

namespace Shika\Repositories;

use Shika\Database\Database;

class UserRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
}