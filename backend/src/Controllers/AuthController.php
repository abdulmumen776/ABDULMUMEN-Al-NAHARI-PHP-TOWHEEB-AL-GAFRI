<?php

namespace Backend\Controllers;

use Backend\Database\Connection;
use Backend\Database\UserRepository;

class AuthController
{
    private UserRepository $users;

    public function __construct()
    {
        $pdo = Connection::make();
        $this->users = new UserRepository($pdo);
    }

    public function register(): string
    {
        $input = $this->input();
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            return $this->json(['message' => 'الرجاء تعبئة جميع الحقول'], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['message' => 'صيغة البريد الإلكتروني غير صحيحة'], 422);
        }

        if ($this->users->findByEmail($email)) {
            return $this->json(['message' => 'البريد مسجل مسبقًا'], 422);
        }

        $userId = $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        $user = $this->users->findById($userId);
        $this->startSession($user);

        return $this->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => $this->publicUser($user),
        ]);
    }

    public function login(): string
    {
        $input = $this->input();
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');

        if ($email === '' || $password === '') {
            return $this->json(['message' => 'البيانات غير مكتملة'], 422);
        }

        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->json(['message' => 'بيانات تسجيل الدخول غير صحيحة'], 401);
        }

        $this->startSession($user);

        return $this->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => $this->publicUser($user),
        ]);
    }

    public function me(): string
    {
        $user = $this->currentUser();
        if (!$user) {
            return $this->json(['message' => 'غير مصرح'], 401);
        }

        return $this->json([
            'user' => $this->publicUser($user),
        ]);
    }

    public function logout(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
        }
        setcookie('backend_user', '', time() - 3600, '/');

        return $this->json(['message' => 'تم تسجيل الخروج']);
    }

    private function input(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw !== false && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return (array) $decoded;
            }
        }
        return $_POST;
    }

    private function startSession(?array $user): void
    {
        if (!$user) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = (int) $user['id'];
        setcookie('backend_user', base64_encode((string) $user['email']), time() + 86400, '/', '', false, true);
    }

    private function currentUser(): ?array
    {
        $id = $_SESSION['user_id'] ?? null;
        if ($id === null) {
            return null;
        }

        return $this->users->findById((int) $id);
    }

    private function publicUser(?array $user): ?array
    {
        if (!$user) {
            return null;
        }

        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];
    }

    private function json(array $data, int $status = 200): string
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
