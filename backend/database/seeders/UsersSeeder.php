<?php

use Backend\Database\Connection;

require_once __DIR__ . '/../../src/Database/Connection.php';

$pdo = Connection::make();

$statement = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
$statement->execute([
    'name' => 'مستخدم تجريبي',
    'email' => 'demo@example.com',
    'password' => password_hash('secret', PASSWORD_BCRYPT),
]);

echo "Users table seeded with a demo account" . PHP_EOL;
