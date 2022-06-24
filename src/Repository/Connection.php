<?php

namespace App\Repository;

use PDO;

class Connection
{
    public PDO $pdo;

    public function __construct(string $dbHost, string $dbName, string $dbUsername, string $dbPassword)
    {
        $this->pdo = new PDO(
            "mysql:host=$dbHost;dbname=$dbName",
            $dbUsername,
            $dbPassword
        );
    }
}