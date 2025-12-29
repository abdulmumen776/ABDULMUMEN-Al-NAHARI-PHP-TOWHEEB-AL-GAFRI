<?php

require_once __DIR__ . '/../../componst/InputComponent.php';
require_once __DIR__ . '/../../componst/SearchButtonComponent.php';

use Componst\InputComponent;
use Componst\SearchButtonComponent;

$nameField = InputComponent::render([
    'name' => 'name',
    'label' => 'الاسم الكامل',
    'placeholder' => 'أدخل اسمك الثلاثي',
    'required' => true,
]);

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
    'hint' => 'اختر كلمة مرور قوية لا تقل عن 8 خانات.',
]);

$submitButton = SearchButtonComponent::render([
    'label' => 'إنشاء حساب',
    'fullWidth' => true,
]);

$backendBase = getenv('BACKEND_BASE_URL') ?: 'http://localhost:9000';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>حساب جديد</h1>
            <p>ابدأ برحلتك داخل النظام عبر تعبئة النموذج التالي.</p>
            <form id="registerForm">
                <?php echo $nameField; ?>
                <?php echo $emailField; ?>
                <?php echo $passwordField; ?>
                <div class="auth-actions">
                    <?php echo $submitButton; ?>
                </div>
            </form>
            <div class="auth-links">
                <a href="login.php">لديك حساب؟ تسجيل الدخول</a>
                <a href="dashboard.php">لوحة المستخدم</a>
            </div>
            <div class="auth-status" id="registerStatus"></div>
        </div>
    </div>
    <script>
    const API_BASE = '<?php echo $backendBase; ?>';
    const registerForm = document.getElementById('registerForm');
    const registerStatus = document.getElementById('registerStatus');

    function setStatus(message, type = 'success') {
        registerStatus.textContent = message;
        registerStatus.classList.add('is-visible');
        registerStatus.classList.toggle('is-success', type === 'success');
        registerStatus.classList.toggle('is-error', type === 'error');
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

    registerForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const payload = Object.fromEntries(new FormData(registerForm).entries());

        try {
            const data = await postJson(`${API_BASE}/auth/register`, payload);
            setStatus(data.message || 'تم إنشاء الحساب', 'success');
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
