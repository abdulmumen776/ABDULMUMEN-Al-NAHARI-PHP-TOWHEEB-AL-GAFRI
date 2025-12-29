<?php

use Backend\Controllers\AuthController;
use Backend\Controllers\HomeController;

function backend_routes(): array
{
    return [
        'GET /' => [HomeController::class, 'index'],
        'POST /auth/register' => [AuthController::class, 'register'],
        'POST /auth/login' => [AuthController::class, 'login'],
        'POST /auth/logout' => [AuthController::class, 'logout'],
        'GET /auth/me' => [AuthController::class, 'me'],
    ];
}
