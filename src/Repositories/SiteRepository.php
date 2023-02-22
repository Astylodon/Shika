<?php

namespace Shika\Repositories;

use Shika\Database\Database;

class SiteRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findById(int $id)
    {
        return $this->database->get("SELECT * FROM `sites` WHERE `id` = ?", $id);
    }

    public function findByKey(string $key)
    {
        return $this->database->get("SELECT * FROM `sites` WHERE `site_key` = ?", $key);
    }

    public function getSites()
    {
        return $this->database->getAll("SELECT * FROM `sites`");
    }
}