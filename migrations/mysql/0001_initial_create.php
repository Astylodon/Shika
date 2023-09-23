<?php

use Shika\Database\Database;
use Shika\Database\Migrations\Migration;

return new class implements Migration
{
    public function up(Database $database)
    {
        $database->exec("ALTER DATABASE DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $database->exec("
            CREATE TABLE sites (
                id          INT(11) AUTO_INCREMENT PRIMARY KEY,
                name        VARCHAR(50) NOT NULL,
                site_key    VARCHAR(24) UNIQUE NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE users (
                id          INT(11) AUTO_INCREMENT PRIMARY KEY,
                username    VARCHAR(20) UNIQUE NOT NULL,
                password    VARCHAR(255) NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE user_api_keys (
                id          INT(11) AUTO_INCREMENT PRIMARY KEY,
                user_id     INT(11) NOT NULL,
                label       VARCHAR(50) NOT NULL,
                api_key     VARCHAR(64) UNIQUE NOT NULL,
                created_at  DATETIME NOT NULL
            )
        ");

        $database->exec("
            CREATE TABLE visits (
                id              INT(11) AUTO_INCREMENT PRIMARY KEY,
                site_id         INT(11) NOT NULL,
                visit_at        DATETIME NOT NULL,
                visit_host      VARCHAR(255) NOT NULL,
                visit_path      VARCHAR(255) NOT NULL,
                referrer_host   VARCHAR(255) NULL DEFAULT NULL,
                referrer_path   VARCHAR(255) NULL DEFAULT NULL
            )
        ");

        $database->exec("ALTER TABLE visits ADD INDEX (site_id, visit_at)");
    }
};