<?php

require_once __DIR__ . '/../../componst/ComponentStyles.php';

use Componst\ComponentStyles;

$styles = ComponentStyles::inject();
$backendBase = getenv('BACKEND_BASE_URL') ?: 'http://localhost:9000';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المستخدم</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php echo $styles; ?>
    <div class="auth-container">
        <div class="auth-card dashboard-card">
            <h1>لوحة معلومات المستخدم</h1>
            <p class="dashboard-meta">تظهر البيانات التالية بعد التحقق من الجلسة في الخادم الخلفي.</p>
            <div id="userBox">
                <p>جارٍ تحميل بياناتك...</p>
            </div>
            <div class="auth-actions">
                <button type="button" id="logoutBtn" class="component-search-button component-search-button--full">تسجيل الخروج</button>
            </div>
            <p class="auth-note">تستطيع العودة إلى <a href="login.php">صفحة تسجيل الدخول</a> أو <a href="register.php">إنشاء حساب جديد</a>.</p>
            <div class="auth-status" id="dashboardStatus"></div>
        </div>
    </div>
    <script>
    const API_BASE = '<?php echo $backendBase; ?>';
    const userBox = document.getElementById('userBox');
    const logoutBtn = document.getElementById('logoutBtn');
    const dashboardStatus = document.getElementById('dashboardStatus');

    function showStatus(message, type = 'success') {
        dashboardStatus.textContent = message;
        dashboardStatus.classList.add('is-visible');
        dashboardStatus.classList.toggle('is-success', type === 'success');
        dashboardStatus.classList.toggle('is-error', type === 'error');
    }

    function readFrontendCookie() {
        const cookies = document.cookie.split(';').map(c => c.trim());
        const entry = cookies.find((c) => c.startsWith('frontend_user='));
        if (entry) {
            return decodeURIComponent(entry.split('=')[1] || '');
        }
        return '';
    }

    async function fetchUser() {
        try {
            const response = await fetch(`${API_BASE}/auth/me`, {
                credentials: 'include',
            });
            if (!response.ok) {
                throw new Error('يجب تسجيل الدخول أولاً');
            }
            const data = await response.json();
            const frontendName = readFrontendCookie();
            userBox.innerHTML = `
                <h2>${data.user.name}</h2>
                <p>البريد الإلكتروني: ${data.user.email}</p>
                <p class="dashboard-meta">مصدر الجلسة: الخادم الخلفي (PHP Session)</p>
                ${frontendName ? `<p>مرحباً بك يا ${frontendName} (معطيات من Cookie الواجهة الأمامية)</p>` : ''}
            `;
        } catch (error) {
            userBox.innerHTML = `<p>${error.message}</p>`;
        }
    }

    async function logout() {
        try {
            const response = await fetch(`${API_BASE}/auth/logout`, {
                method: 'POST',
                credentials: 'include'
            });
            if (!response.ok) {
                throw new Error('تعذر تسجيل الخروج');
            }
            document.cookie = 'frontend_user=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
            showStatus('تم تسجيل الخروج بنجاح');
            userBox.innerHTML = '<p>تم إنهاء الجلسة، يمكنك تسجيل الدخول من جديد.</p>';
        } catch (error) {
            showStatus(error.message, 'error');
        }
    }

    logoutBtn.addEventListener('click', logout);
    fetchUser();
    </script>
</body>
</html>
