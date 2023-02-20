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
    
        return $sth->fetch(\PDO::FETCH_OBJ);
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
}