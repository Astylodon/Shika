<?php

namespace Shika\Repositories;

use Shika\Database\Database;

class VisitRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addVisit(array $visit)
    {
        $this->database->insert("visits", $visit);
    }

    public function getTotalVisits(int $site)
    {
        return $this->database->getColumn("SELECT COUNT(*) FROM `visits` WHERE `site_id` = ?", $site);
    }

    public function groupBy(string $field, string $name, int $from = 0, int $site = 0)
    {
        $query = "SELECT `$field` AS `$name`, count(*) as `count` FROM `visits` WHERE `$field` IS NOT NULL";
        $params = [];
        
        if ($site > 0)
        {
            $query .= " AND `site_id` = ?";
            array_push($params, $site);
        }

        if ($from > 0)
        {
            $query .= " AND `visit_at` > ?";
            array_push($params, gmdate("Y-m-d H:i:s", $from));
        }

        $query .= " GROUP BY `$field` ORDER BY `count` DESC";

        return $this->database->getAll($query, ...$params);
    }
}