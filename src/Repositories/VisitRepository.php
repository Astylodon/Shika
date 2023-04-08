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
        return $this->database->getScalar("SELECT COUNT(*) FROM `visits` WHERE `site_id` = ?", $site);
    }

    public function getReferrers(int $from = 0, int $site = 0)
    {
        $query = "SELECT `referrer_host` AS `referrer`, count(*) as `count` FROM `visits` WHERE `referrer_host` IS NOT NULL";
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

        $query .= " GROUP BY `referrer_host` ORDER BY `count` DESC";

        return $this->database->getAll($query, ...$params);
    }

    public function getPages(int $from = 0, int $site = 0)
    {
        $query = "SELECT `visit_path` AS `path`, count(*) as `count` FROM `visits` WHERE `visit_path` IS NOT NULL";
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

        $query .= " GROUP BY `visit_path` ORDER BY `count` DESC";

        return $this->database->getAll($query, ...$params);
    }
}