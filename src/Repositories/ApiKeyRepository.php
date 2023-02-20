<?php

namespace Shika\Repositories;

use Shika\Database\Database;

class ApiKeyRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findByKey(string $key)
    {
        return $this->database->get("SELECT * FROM `user_api_keys` WHERE key = ?", $key);
    }
}