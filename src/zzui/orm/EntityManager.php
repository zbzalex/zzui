<?php

namespace zzui\orm;

class EntityManager
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
