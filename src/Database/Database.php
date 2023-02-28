<?php

namespace Shika\Database;

class Database
{
    private \PDO $conn;

    public function __construct(string $dsn, string|null $username, string|null $password)
    {
        $this->conn = new \PDO($dsn, $username, $password);
    }

    public function get(string $query, ...$params)
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

    public function getAll(string $query, ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
    
        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getScalar(string $query, ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
    
        return $sth->fetchColumn();
    }

    public function exec(string $query, ...$params)
    {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
    
        return $sth->columnCount();
    }

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
}