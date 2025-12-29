<?php

namespace Backend\Controllers;

class HomeController
{
    public function index(): string
    {
        $name = 'نظام الـ Backend';
        return <<<HTML
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; padding: 3rem; background: #f4f6fb; color: #0f172a; }
        .card { max-width: 540px; margin: 0 auto; background: #fff; border-radius: 1rem; padding: 2rem; box-shadow: 0 25px 45px -25px rgba(15,23,42,.35); }
        h1 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="card">
        <h1>مرحباً بك في {$name}</h1>
        <p>هذه صفحة افتراضية يمكنك الانطلاق منها لبناء واجهات برمجية أو لوحات تحكم.</p>
    </div>
</body>
</html>
HTML;
    }
}
