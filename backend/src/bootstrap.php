<?php

declare(strict_types=1);

require_once __DIR__ . '/Database/Connection.php';
require_once __DIR__ . '/Database/UserRepository.php';
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Routes/web.php';

function backend_handle_request(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $routes = backend_routes();
    $key = sprintf('%s %s', $method, $path);

    if (isset($routes[$key])) {
        [$controller, $handler] = $routes[$key];
        if (class_exists($controller) && method_exists($controller, $handler)) {
            $instance = new $controller();
            return (string) $instance->$handler();
        }
    }

    http_response_code(404);
    return json_encode(['message' => 'المسار غير موجود'], JSON_UNESCAPED_UNICODE);
}
