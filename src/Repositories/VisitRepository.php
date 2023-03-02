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

    public function getReferrers(int $site = 0)
    {
        $query = "SELECT `referrer_host` AS `referrer`, count(*) as `count` FROM `visits` WHERE `referrer_host` IS NOT NULL";
        $params = [];
        
        if ($site > 0)
        {
            $query .= " AND `site_id` = ?";
            
            array_push($params, $site);
        }

        $query .= " GROUP BY `referrer_host` ORDER BY `count` DESC";

        return $this->database->getAll($query, ...$params);
    }
}