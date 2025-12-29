<?php

declare(strict_types=1);

session_start();

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    'http://localhost',
];

if ($origin !== '' && in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: ' . 'true');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../src/bootstrap.php';

echo backend_handle_request();
