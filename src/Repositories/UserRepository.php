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

    public function findById(int $id)
    {
        return $this->database->get("SELECT * FROM `users` WHERE `id` = ?", $id);
    }

    public function findByName(string $username)
    {
        return $this->database->get("SELECT * FROM `users` WHERE `username` = ?", $username);
    }

    public function getUsers()
    {
        return $this->database->getAll("SELECT * FROM `users`");
    }
}