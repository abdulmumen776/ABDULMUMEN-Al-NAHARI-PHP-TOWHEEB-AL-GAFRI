<?php

require_once __DIR__ . '/../../componst/InputComponent.php';
require_once __DIR__ . '/../../componst/SearchButtonComponent.php';

use Componst\InputComponent;
use Componst\SearchButtonComponent;

$emailField = InputComponent::render([
    'name' => 'email',
    'label' => 'البريد الإلكتروني',
    'placeholder' => 'example@mail.com',
    'type' => 'email',
    'required' => true,
]);

$passwordField = InputComponent::render([
    'name' => 'password',
    'label' => 'كلمة المرور',
    'type' => 'password',
    'required' => true,
]);

$submitButton = SearchButtonComponent::render([
    'label' => 'تسجيل الدخول',
    'fullWidth' => true,
]);

$backendBase = getenv('BACKEND_BASE_URL') ?: 'http://localhost:9000';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>تسجيل الدخول</h1>
            <p>ادخل بياناتك للوصول إلى لوحة المستخدم.</p>
            <form id="loginForm">
                <?php echo $emailField; ?>
                <?php echo $passwordField; ?>
                <div class="auth-actions">
                    <?php echo $submitButton; ?>
                </div>
            </form>
            <div class="auth-links">
                <a href="register.php">إنشاء حساب جديد</a>
                <a href="dashboard.php">الذهاب للوحة</a>
            </div>
            <div class="auth-status" id="loginStatus"></div>
        </div>
    </div>
    <script>
    const API_BASE = '<?php echo $backendBase; ?>';
    const loginForm = document.getElementById('loginForm');
    const loginStatus = document.getElementById('loginStatus');

    function setStatus(message, type = 'success') {
        loginStatus.textContent = message;
        loginStatus.classList.add('is-visible');
        loginStatus.classList.toggle('is-success', type === 'success');
        loginStatus.classList.toggle('is-error', type === 'error');
    }

    async function postJson(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(payload)
        });

        const text = await response.text();
        let data = {};
        try {
            data = text ? JSON.parse(text) : {};
        } catch (err) {
            data = { message: text };
        }

        if (!response.ok) {
            throw new Error(data.message || 'حدث خطأ غير متوقع');
        }

        return data;
    }

    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(loginForm);
        const payload = Object.fromEntries(formData.entries());

        try {
            const data = await postJson(`${API_BASE}/auth/login`, payload);
            setStatus(data.message || 'تم تسجيل الدخول', 'success');
            document.cookie = `frontend_user=${encodeURIComponent(data.user.name)};path=/;max-age=86400`;
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1200);
        } catch (error) {
            setStatus(error.message, 'error');
        }
    });
    </script>
</body>
</html>
