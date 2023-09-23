<?php

namespace Shika\Database\Migrations;

use Shika\Database\Database;

class Migrate
{    
    private Database $database;

    private string $migrationsPath;
    private array $migrations;
    private string $driver;

    public function __construct(Database $database, string $driver)
    {
        $this->database = $database;

        $this->migrationsPath = getcwd() . DIRECTORY_SEPARATOR . "migrations" . DIRECTORY_SEPARATOR . $driver;
        $this->migrations = [];
        $this->driver = $driver;
    }

    /**
     * Find all migrations in the migrations folder
     */
    public function findMigrations()
    {
        $files = scandir($this->migrationsPath);

        foreach ($files as $file)
        {
            if (strpos($file, ".php") !== false)
            {
                array_push($this->migrations, $file);
            }
        }
    }

    /**
     * Migrate the current database
     */
    public function migrate()
    {
        $this->ensureMigrations();

        // fetch the applied migrations
        $applied = $this->database->getAll("SELECT migration FROM migrations");
        $applied = array_map(function($migration) { return $migration->migration; }, $applied);

        // remove migrations we already applied
        $migrations = array_diff($this->migrations, $applied);

        if (count($migrations) < 1)
        {
            echo "No migrations to apply\n";
        }

        foreach ($migrations as $filename)
        {
            $path = $this->migrationsPath . DIRECTORY_SEPARATOR . $filename;

            echo $filename;

            $migration = require $path;
            $this->executeMigration($filename, $migration);

            echo " \033[32mOK\033[0m\n";
        }
    }

    private function executeMigration(string $filename, Migration $migration)
    {
        // execute the migration up
        $migration->up($this->database);

        // add to migration history
        $this->database->exec("INSERT INTO migrations VALUES (?)", $filename);
    }

    private function ensureMigrations()
    {
        if ($this->driver == "sqlite")
        {
            $this->database->exec("CREATE TABLE IF NOT EXISTS migrations (migration TEXT PRIMARY KEY)");
        }
        else
        {
            $this->database->exec("CREATE TABLE IF NOT EXISTS migrations (migration VARCHAR(50) PRIMARY KEY)");
        }
    }
}