<?php

namespace zzui\orm;

class Connection
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function executeNativeQuery($query, array $params = [])
    {
        $st = $this->pdo->prepare($query);
        $st->execute($params);
        return $st;
    }
}
