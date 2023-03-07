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

    public function getPages(int $site = 0)
    {
        $query = "SELECT `visit_path` AS `path`, count(*) as `count` FROM `visits` WHERE `visit_path` IS NOT NULL";
        $params = [];
        
        if ($site > 0)
        {
            $query .= " AND `site_id` = ?";
            
            array_push($params, $site);
        }

        $query .= " GROUP BY `visit_path` ORDER BY `count` DESC";

        return $this->database->getAll($query, ...$params);
    }
}