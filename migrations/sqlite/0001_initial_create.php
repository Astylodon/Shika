<?php

use Astylodon\Migrations\Database\DatabaseInterface;
use Astylodon\Migrations\Migration;

return new class implements Migration
{
    public function up(DatabaseInterface $database)
    {
        $database->exec("
            CREATE TABLE sites (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                name        TEXT NOT NULL,
                site_key    TEXT NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE users (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                username    TEXT COLLATE NOCASE NOT NULL,
                password    TEXT NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE user_api_keys (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id     INTEGER NOT NULL REFERENCES users(id),
                label       TEXT NOT NULL,
                api_key     TEXT UNIQUE NOT NULL,
                created_at  TEXT NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE visits (
                id              INTEGER PRIMARY KEY AUTOINCREMENT,
                site_id         INTEGER NOT NULL REFERENCES sites(id),
                visit_at        TEXT NOT NULL,
                visit_host      TEXT NOT NULL,
                visit_path      TEXT NOT NULL,
                referrer_host   TEXT,
                referrer_path   TEXT
            )
        ");

        $database->exec("CREATE INDEX ix_sites_site_key ON sites(site_key)");
        $database->exec("CREATE INDEX ix_users_username ON users(username)");
        $database->exec("CREATE INDEX ix_visits_site_id_visit_at ON visits(site_id, visit_at)");
    }
};