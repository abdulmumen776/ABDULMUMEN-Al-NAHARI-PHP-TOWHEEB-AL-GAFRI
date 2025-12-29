<?php

namespace Backend\Database;

use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user === false ? null : $user;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user === false ? null : $user;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
