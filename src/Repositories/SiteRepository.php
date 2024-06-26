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

    public function getAllowedHosts(int $id)
    {
        return $this->database->getColumns("SELECT allowed_host FROM `site_allowed_hosts` WHERE `site_id` = ?", $id);
    }

    public function addSite(string $name, string $key)
    {
        $this->database->insert("sites", [ "name" => $name, "site_key" => $key ]);
    }
}