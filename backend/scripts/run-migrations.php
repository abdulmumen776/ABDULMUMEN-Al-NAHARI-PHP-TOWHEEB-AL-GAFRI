<?php

declare(strict_types=1);

use Backend\Database\Connection;

require_once __DIR__ . '/../src/Database/Connection.php';

$pdo = Connection::make();
$migrations = glob(__DIR__ . '/../database/migrations/*.php');
sort($migrations);

foreach ($migrations as $file) {
    $migration = require $file;
    if (is_object($migration) && method_exists($migration, 'up')) {
        $migration->up($pdo);
        echo 'Migrated: ' . basename($file) . PHP_EOL;
    }
}
