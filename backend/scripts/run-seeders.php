<?php

declare(strict_types=1);

$seeders = glob(__DIR__ . '/../database/seeders/*.php');
sort($seeders);

foreach ($seeders as $file) {
    require $file;
}
