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

    public function getKeys()
    {
        return $this->database->getAll("SELECT user_api_keys.*, users.username FROM user_api_keys INNER JOIN users ON user_api_keys.user_id = users.id");
    }

    public function addKey(string $label, string $key, int $user)
    {
        $data = [
            "user_id" => $user,
            "label" => $label,
            "key" => $key,
            "created_at" => gmdate("Y-m-d H:i:s")
        ];

        $this->database->insert("user_api_keys", $data);
    }
}