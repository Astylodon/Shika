<?php

use Shika\Database\Database;
use Shika\Database\Migrations\Migration;

return new class implements Migration
{
    public function up(Database $database)
    {
        $database->exec("
            CREATE TABLE sites (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                name        TEXT,
                site_key    TEXT
            )
        ");

        $database->exec("
            CREATE TABLE users (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                username    TEXT COLLATE NOCASE,
                password    TEXT
            )
        ");

        $database->exec("
            CREATE TABLE user_api_keys (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id     INTEGER REFERENCES users(id),
                label       TEXT,
                key         TEXT UNIQUE,
                created_at  TEXT
            )
        ");

        $database->exec("
            CREATE TABLE visits (
                id              INTEGER PRIMARY KEY AUTOINCREMENT,
                site_id         INTEGER REFERENCES sites(id),
                visit_at        TEXT,
                visit_host      TEXT,
                visit_path      TEXT,
                referrer_host   TEXT,
                referrer_path   TEXT
            )
        ");

        $database->exec("CREATE INDEX ix_sites_site_key ON sites(site_key)");
        $database->exec("CREATE INDEX ix_users_username ON users(username)");
        $database->exec("CREATE INDEX ix_visits_site_id_visit_at ON visits(site_id, visit_at)");
    }
};