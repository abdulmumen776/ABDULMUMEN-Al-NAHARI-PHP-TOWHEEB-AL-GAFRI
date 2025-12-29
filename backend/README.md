# Backend Skeleton

هذا المجلد يحتوي على هيكل بسيط لتطبيق PHP يستخدمه كخلفية للمشروع:

- `public/index.php` نقطة الدخول التي تستدعي bootstrap.
- `src/bootstrap.php` مسؤول عن تحميل المتحكمات وتعريف الدوال العامة.
- `src/Controllers/` ضع المتحكمات (Controllers) هنا، يوجد مثال `HomeController`.
- `src/Routes/web.php` تعريف مسارات بسيطة يمكن توسيعها لاحقًا.
- `storage/logs/` مجلد مخصص لتخزين السجلات ويمكنك وضع ملفات log بداخله.
- `database/` يضم إعدادات الاتصال، ملفات الترحيل (migrations)، المزارعين (seeders) وأي factories مستقبلية.
- `scripts/` سكربتات مساعدة لتشغيل الترحيلات أو المزارعين.
- `src/Controllers/AuthController.php` يحتوي على عمليات التسجيل، تسجيل الدخول، تسجيل الخروج، وجلب المستخدم الحالي.

## التشغيل

```bash
php -S localhost:9000 -t backend/public
```

بعد تشغيل الخادم زر `http://localhost:9000/` لمعاينة الصفحة الافتراضية.

## إعداد قاعدة البيانات

1. انسخ ملف البيئة:
   ```bash
   cp backend/.env.example backend/.env
   ```
   ثم عيّن القيم الصحيحة لمعلومات الاتصال بقاعدة البيانات.

2. شغل الترحيلات:
   ```bash
   php backend/scripts/run-migrations.php
   ```

3. أضف بيانات تجريبية (اختياري):
   ```bash
   php backend/scripts/run-seeders.php
   ```

ستجد مثالاً لترحيل إنشاء جدول المستخدمين في `database/migrations/2024_01_01_000000_create_users_table.php` ومثال Seeder في `database/seeders/UsersSeeder.php`.

## واجهات المصادقة

| المسار | الطريقة | الوصف |
| ------ | ------- | ----- |
| `/auth/register` | `POST` | إنشاء مستخدم جديد وتفعيل الجلسة مباشرة |
| `/auth/login` | `POST` | التحقق من البريد/كلمة المرور وتخزين `$_SESSION['user_id']` |
| `/auth/me` | `GET` | إعادة بيانات المستخدم الحالي إذا كانت الجلسة فعالة |
| `/auth/logout` | `POST` | إنهاء الجلسة وحذف Cookie `backend_user` |

- يتم الرد بصيغة JSON مع رسائل باللغة العربية.
- الخادم يقوم بإعداد ترويسات CORS ليسمح بطلبات من `http://localhost:8000` مع `credentials: include`.
- عند نجاح المصادقة يتم إنشاء Cookie باسم `backend_user` لسهولة التحقق اليدوي بالإضافة إلى Session PHP القياسي.
