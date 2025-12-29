# Database Layer

- `config.php`: إعدادات الاتصال بقاعدة البيانات مستمدة من متغيرات البيئة (`DB_HOST`, `DB_DATABASE` ...).
- `migrations/`: ضع ملفات إنشاء الجداول. يوجد مثال لإنشاء جدول المستخدمين.
- `seeders/`: سكربتات لملء البيانات الابتدائية.
- `factories/`: مساحة مخصّصة لاحقًا لتوليد بيانات وهمية.

## المتغيرات البيئية المقترحة

```
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_db
DB_USERNAME=root
DB_PASSWORD=
```

## تشغيل مثال الترحيل

```bash
php backend/scripts/run-migrations.php
```

> يمكنك تعديل السكربت حسب احتياجك أو تنفيذ الملفات يدويًا.
