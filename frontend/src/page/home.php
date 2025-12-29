<?php

require_once __DIR__ . '/../../componst/InputComponent.php';
require_once __DIR__ . '/../../componst/SelectComponent.php';
require_once __DIR__ . '/../../componst/CardComponent.php';
require_once __DIR__ . '/../../componst/SearchButtonComponent.php';
require_once __DIR__ . '/../../componst/ThemeToggleComponent.php';

use Componst\InputComponent;
use Componst\SelectComponent;
use Componst\CardComponent;
use Componst\SearchButtonComponent;
use Componst\ThemeToggleComponent;

$input = InputComponent::render([
    'name' => 'full_name',
    'label' => 'الاسم الكامل',
    'placeholder' => 'أدخل اسمك هنا',
    'hint' => 'سوف نستخدم الاسم في الترحيب بك داخل النظام.',
    'required' => true,
]);

$select = SelectComponent::render([
    'name' => 'track',
    'label' => 'المسار الدراسي',
    'options' => [
        ['label' => 'علوم الحاسب', 'value' => 'cs'],
        ['label' => 'نظم المعلومات', 'value' => 'is'],
        ['label' => 'تقنية المعلومات', 'value' => 'it'],
    ],
    'hint' => 'يمكنك تغيير المسار لاحقًا.',
]);

$card = CardComponent::render([
    'title' => 'مرحباً بك في لوحة التجربة',
    'content' => 'استخدم المكونات الجاهزة لصناعة صفحات متناسقة بسرعة. يمكنك تعديل الألوان، النصوص، وحتى إضافة مكوّنات أخرى بسهولة.',
    'meta' => ['نسخة 1.0', 'اليوم'],
    'actions' => [
        ['label' => 'ابدأ الآن', 'href' => '#'],
    ],
]);

$searchButton = SearchButtonComponent::render([
    'label' => 'بحث سريع',
]);

$themeToggle = ThemeToggleComponent::render();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة تجريبية للمكوّنات</title>
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
    <div class="page-wrapper">
        <header class="page-hero">
            <div>
                <h1 class="page-hero__title">أهلاً بك 👋</h1>
                <p class="page-hero__subtitle">هذه الصفحة تعرض مكوّنات قابلة لإعادة الاستخدام للوضع النهاري والليلي.</p>
            </div>
            <div>
                <?php echo $themeToggle; ?>
            </div>
        </header>

        <section class="page-grid">
            <div class="page-form">
                <h3>نموذج سريع</h3>
                <?php
                echo $input;
                echo $select;
                ?>
                <div class="page-form__actions">
                    <?php echo $searchButton; ?>
                </div>
            </div>

            <div class="page-card">
                <?php echo $card; ?>
            </div>
        </section>

        <section class="page-auth-links">
            <h3>بوابة التسجيل والدخول</h3>
            <p>لتجربة نظام المصادقة والجلسات، يمكنك زيارة الصفحات التالية:</p>
            <div class="page-auth-links__actions">
                <a class="component-card__btn" href="login.php">تسجيل الدخول</a>
                <a class="component-card__btn" href="register.php">إنشاء حساب</a>
                <a class="component-card__btn" href="dashboard.php">لوحة المستخدم</a>
            </div>
        </section>
    </div>
</body>
</html>
