<?php

namespace Backend\Database;

use PDO;
use PDOException;

class Connection
{
    public static function make(): PDO
    {
        $config = require __DIR__ . '/../../database/config.php';

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            return new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
