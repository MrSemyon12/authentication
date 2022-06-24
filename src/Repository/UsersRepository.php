<?php

namespace App\Repository;

use App\Model\User;

class UsersRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->connection->pdo->exec('CREATE TABLE IF NOT EXISTS users(login VARCHAR(255), password VARCHAR(255), PRIMARY KEY(login))');
    }

    public function findUser(string $login): ?User
    {
        $query = 'SELECT * FROM users WHERE login = :login';
        $tmp = $this->connection->pdo->prepare($query);
        $tmp->execute(['login' => $login]);
        $data = $tmp->fetchAll();
        if (!empty($data)) {
            return new User($login, $data[0]['password']);
        }
        else {
            return null;
        }
    }

    public function addUser(string $login, string $password): void
    {
        if ($this->findUser($login) == null)
        {
            $query = 'INSERT INTO users(login, password) VALUES(:login, :password)';
            $tmp = $this->connection->pdo->prepare($query);
            $tmp->execute(['login' => $login, 'password' => $password]);
        }
    }
}