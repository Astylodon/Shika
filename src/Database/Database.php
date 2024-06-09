<?php

namespace Shika\Database;

use Astylodon\Migrations\Database\DatabaseInterface;

class Database implements DatabaseInterface
{
    private \PDO $conn;

    public function __construct(string $dsn, string|null $username, string|null $password)
    {
        $this->conn = new \PDO($dsn, $username, $password);
    }

    /**
     * Executes a query and returns the first row
     * 
     * @param string $query The query to execute
     * @param mixed $params The parameters to bind in the query
     */
    public function get(string $query, mixed ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
    
        $result = $sth->fetch(\PDO::FETCH_OBJ);

        // return null instead of false if no row
        if ($result === false)
        {
            return null;
        }

        return $result;
    }

    /**
     * Executes a query and returns all rows
     * 
     * @param string $query The query to execute
     * @param mixed $params The parameters to bind in the query
     */
    public function getAll(string $query, mixed ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Executes a query and returns a single column
     * 
     * @param string $query The query to execute
     * @param mixed $params The parameters to bind in the query
     */
    public function getColumn(string $query, mixed ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);

        return $sth->fetchColumn();
    }

    /**
     * Executes a query and returns a single column for all rows
     * 
     * @param string $query The query to execute
     * @param mixed $params The parameters to bind in the query
     */
    public function getColumns(string $query, mixed ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);

        return $sth->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Executes a store query and returns the number of affected rows
     * 
     * @param string $query The query to execute
     * @param mixed $params The parameters to bind in the query
     */
    public function exec(string $query, mixed ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);

        return $sth->rowCount();
    }

    /**
     * Inserts values into a table, this builds a query and executes it after
     * 
     * @param string $table The name of the table
     * @param array $values An array with keys matching the column names
     */
    public function insert(string $table, array $values)
    {
        // build an insert query
        $columns = "";

        foreach ($values as $key => $value)
        {
            $columns .= "`$key`, ";
        }

        $columns = substr($columns, 0, -2);
        $params = implode(", ", array_fill(0, count($values), "?"));

        $query = "INSERT INTO `$table` ($columns) VALUES ($params)";

        // execute store query
        return $this->exec($query, ...array_values($values));
    }

    /**
     * Gets the name of the current connection driver
     */
    public function getDriver(): string
    {
        return $this->conn->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }
}