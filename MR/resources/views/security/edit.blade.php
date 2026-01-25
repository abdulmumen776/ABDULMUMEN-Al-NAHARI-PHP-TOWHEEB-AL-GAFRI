@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تعديل إعداد الأمان</h2>
            <p class="text-gray-600 dark:text-gray-400">تحديث معلومات إعداد الأمان في النظام</p>
        </div>
        <a href="{{ route('security.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="securityEdit()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">تعديل إعداد الأمان</h1>
                    <p class="text-indigo-100">تحديث معلومات إعداد الأمان الحالي في النظام</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-11a2 2 0 00-2-2H6a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11H6m3 0h18M9 11v6m0 4h.01M15 17H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('security.update', $security) }}" method="POST" @submit.prevent="submitForm">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات أساسية</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم الإعداد <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required
                                        placeholder="أدخل اسم الإعداد"
                                        :error="errors.name"
                                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع الإعداد <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type" required
                                            class="form-input">
                                        <option value="firewall" {{ $security->type == 'firewall' ? 'selected' : '' }}>جدار الحماية</option>
                                        <option value="ssl" {{ $security->type == 'ssl' ? 'selected' : '' }}>شهادة SSL</option>
                                        <option value="authentication" {{ $security->type == 'authentication' ? 'selected' : '' }}>المصادقة</option>
                                        <option value="encryption" {{ $security->type == 'encryption' ? 'selected' : '' }}>التشفير</option>
                                        <option value="access_control" {{ $security->type == 'access_control' ? 'selected' : '' }}>التحكم في الوصول</option>
                                        <option value="monitoring" {{ $security->type == 'monitoring' ? 'selected' : '' }}>مراقبة الأمان</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="form-input">
                                        <option value="active" {{ $security->status == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ $security->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="warning" {{ $security->status == 'warning' ? 'selected' : '' }}>تحذير</option>
                                        <option value="critical" {{ $security->status == 'critical' ? 'selected' : '' }}>حرج</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الأولوية
                                    </label>
                                    <select name="priority" id="priority"
                                            class="form-input">
                                        <option value="low" {{ $security->priority == 'low' ? 'selected' : '' }}>منخفضة</option>
                                        <option value="medium" {{ $security->priority == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                        <option value="high" {{ $security->priority == 'high' ? 'selected' : '' }}>عالية</option>
                                        <option value="critical" {{ $security->priority == 'critical' ? 'selected' : '' }}>حرجة</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العميل
                                    </label>
                                    <select name="client_id" id="client_id"
                                            class="form-input">
                                        <option value="">اختر العميل</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ $security->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Configuration -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات والتكوين</h3>
                            
                            <div class="space-y-4">
                                <div x-show="form.type === 'firewall'">
                                    <label for="firewall_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        قواعد جدار الحماية
                                    </label>
                                    <textarea name="firewall_rules" id="firewall_rules" rows="4"
                                              x-model="form.firewall_rules"
                                              placeholder='{"allow": ["80", "443"], "deny": ["22", "3389"]}'
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div x-show="form.type === 'ssl'">
                                    <label for="ssl_certificate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        شهادة SSL
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="ssl_certificate" 
                                        id="ssl_certificate"
                                        placeholder="*.example.com"
                                        model="form.ssl_certificate"
                                        icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </div>

                                <div x-show="form.type === 'ssl'">
                                    <label for="ssl_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تاريخ انتهاء الشهادة
                                    </label>
                                    <x-input 
                                        type="date" 
                                        name="ssl_expiry" 
                                        id="ssl_expiry"
                                        model="form.ssl_expiry"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div x-show="form.type === 'authentication'">
                                    <label for="auth_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        طريقة المصادقة
                                    </label>
                                    <select name="auth_method" id="auth_method"
                                            x-model="form.auth_method"
                                            class="form-input">
                                        <option value="2fa" {{ $security->auth_method == '2fa' ? 'selected' : '' }}>المصادقة الثنائية</option>
                                        <option value="oauth" {{ $security->auth_method == 'oauth' ? 'selected' : '' }}>OAuth</option>
                                        <option value="ldap" {{ $security->auth_method == 'ldap' ? 'selected' : '' }}>LDAP</option>
                                        <option value="saml" {{ $security->auth_method == 'saml' ? 'selected' : '' }}>SAML</option>
                                        <option value="jwt" {{ $security->auth_method == 'jwt' ? 'selected' : '' }}>JWT</option>
                                    </select>
                                </div>

                                <div x-show="form.type === 'encryption'">
                                    <label for="encryption_algorithm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        خوارزمية التشفير
                                    </label>
                                    <select name="encryption_algorithm" id="encryption_algorithm"
                                            x-model="form.encryption_algorithm"
                                            class="form-input">
                                        <option value="AES-256" {{ $security->encryption_algorithm == 'AES-256' ? 'selected' : '' }}>AES-256</option>
                                        <option value="AES-128" {{ $security->encryption_algorithm == 'AES-128' ? 'selected' : '' }}>AES-128</option>
                                        <option value="RSA-2048" {{ $security->encryption_algorithm == 'RSA-2048' ? 'selected' : '' }}>RSA-2048</option>
                                        <option value="RSA-4096" {{ $security->encryption_algorithm == 'RSA-4096' ? 'selected' : '' }}>RSA-4096</option>
                                        <option value="SHA-256" {{ $security->encryption_algorithm == 'SHA-256' ? 'selected' : '' }}>SHA-256</option>
                                    </select>
                                </div>

                                <div x-show="form.type === 'access_control'">
                                    <label for="access_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مستوى الوصول
                                    </label>
                                    <select name="access_level" id="access_level"
                                            x-model="form.access_level"
                                            class="form-input">
                                        <option value="public" {{ $security->access_level == 'public' ? 'selected' : '' }}>عام</option>
                                        <option value="private" {{ $security->access_level == 'private' ? 'selected' : '' }}>خاص</option>
                                        <option value="restricted" {{ $security->access_level == 'restricted' ? 'selected' : '' }}>مقيد</option>
                                        <option value="admin" {{ $security->access_level == 'admin' ? 'selected' : '' }}>مدير</option>
                                    </select>
                                </div>

                                <div x-show="form.type === 'monitoring'">
                                    <label for="monitoring_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        فترة المراقبة (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="monitoring_interval" 
                                        id="monitoring_interval"
                                        placeholder="5"
                                        model="form.monitoring_interval"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Monitoring Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات المراقبة</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="threshold_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        قيمة العتبة
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="threshold_value" 
                                        id="threshold_value"
                                        placeholder="100"
                                        model="form.threshold_value"
                                        icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2zm0 0V9a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012-2z"
                                    />
                                </div>

                                <div>
                                    <label for="threshold_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع العتبة
                                    </label>
                                    <select name="threshold_type" id="threshold_type"
                                            x-model="form.threshold_type"
                                            class="form-input">
                                        <option value="attempts" {{ $security->threshold_type == 'attempts' ? 'selected' : '' }}>عدد المحاولات</option>
                                        <option value="time" {{ $security->threshold_type == 'time' ? 'selected' : '' }}>الوقت</option>
                                        <option value="percentage" {{ $security->threshold_type == 'percentage' ? 'selected' : '' }}>النسبة المئوية</option>
                                        <option value="count" {{ $security->threshold_type == 'count' ? 'selected' : '' }}>العدد</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="alert_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تفعيل التنبيهات
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.alert_enabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.alert_enabled">
                                    <label for="alert_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة التنبيه
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="alert_threshold" 
                                        id="alert_threshold"
                                        placeholder="10"
                                        model="form.alert_threshold"
                                        icon="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="auto_block" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحظر التلقائي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_block" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.auto_block">
                                    <label for="block_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مدة الحظر (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="block_duration" 
                                        id="block_duration"
                                        placeholder="30"
                                        model="form.block_duration"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الوصف والملاحظات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وصف الإعداد
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً مفصلاً لإعداد الأمان"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ملاحظات إضافية
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              x-model="form.notes"
                                              placeholder="أدخل أي ملاحظات إضافية"
                                              class="form-input resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- IP Configuration -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات IP</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="allowed_ips" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عناوين IP المسموح بها
                                    </label>
                                    <textarea name="allowed_ips" id="allowed_ips" rows="3"
                                              x-model="form.allowed_ips"
                                              placeholder="192.168.1.0/24, 10.0.0.0/8"
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="blocked_ips" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عناوين IP المحظورة
                                    </label>
                                    <textarea name="blocked_ips" id="blocked_ips" rows="3"
                                              x-model="form.blocked_ips"
                                              placeholder="0.0.0.0, 192.168.1.100"
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="geo_restriction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        القيود الجغرافية
                                    </label>
                                    <select name="geo_restriction" id="geo_restriction"
                                            x-model="form.geo_restriction"
                                            class="form-input">
                                        <option value="none" {{ $security->geo_restriction == 'none' ? 'selected' : '' }}>بدون قيود</option>
                                        <option value="whitelist" {{ $security->geo_restriction == 'whitelist' ? 'selected' : '' }}>القائمة البيضاء</option>
                                        <option value="blacklist" {{ $security->geo_restriction == 'blacklist' ? 'selected' : '' }}>القائمة السوداء</option>
                                    </select>
                                </div>

                                <div x-show="form.geo_restriction !== 'none'">
                                    <label for="allowed_countries" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الدول المسموح بها/المحظورة
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="allowed_countries" 
                                        id="allowed_countries"
                                        placeholder="SA, AE, US"
                                        model="form.allowed_countries"
                                        icon="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 3h-1A2.5 2.5 0 007 5.5v1.435M8 3.935V3.5A2.5 2.5 0 0010.5 1h-1A2.5 2.5 0 007 3.5v.435"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات المتقدمة</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="log_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مستوى التسجيل
                                    </label>
                                    <select name="log_level" id="log_level"
                                            x-model="form.log_level"
                                            class="form-input">
                                        <option value="debug" {{ $security->log_level == 'debug' ? 'selected' : '' }}>تصحيح</option>
                                        <option value="info" {{ $security->log_level == 'info' ? 'selected' : '' }}>معلومات</option>
                                        <option value="warning" {{ $security->log_level == 'warning' ? 'selected' : '' }}>تحذير</option>
                                        <option value="error" {{ $security->log_level == 'error' ? 'selected' : '' }}>خطأ</option>
                                        <option value="critical" {{ $security->log_level == 'critical' ? 'selected' : '' }}>حرج</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="retention_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        فترة الاحتفاظ بالسجلات (بالأيام)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="retention_days" 
                                        id="retention_days"
                                        placeholder="30"
                                        model="form.retention_days"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div>
                                    <label for="backup_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تفعيل النسخ الاحتياطي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.backup_enabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.backup_enabled">
                                    <label for="backup_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تكرار النسخ الاحتياطي
                                    </label>
                                    <select name="backup_frequency" id="backup_frequency"
                                            x-model="form.backup_frequency"
                                            class="form-input">
                                        <option value="daily" {{ $security->backup_frequency == 'daily' ? 'selected' : '' }}>يومي</option>
                                        <option value="weekly" {{ $security->backup_frequency == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                        <option value="monthly" {{ $security->backup_frequency == 'monthly' ? 'selected' : '' }}>شهري</option>
                                        <option value="yearly" {{ $security->backup_frequency == 'yearly' ? 'selected' : '' }}>سنوي</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('security.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">تحديث الإعداد</span>
                        <span x-show="loading">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 8 8 0 018 0z"></path>
                            </svg>
                            جاري التحديث...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function securityEdit() {
            return {
                loading: false,
                errors: {},
                form: {
                    name: '{{ $security->name }}',
                    type: '{{ $security->type }}',
                    status: '{{ $security->status }}',
                    priority: '{{ $security->priority ?? 'medium' }}',
                    client_id: '{{ $security->client_id ?? '' }}',
                    firewall_rules: '{{ $security->firewall_rules ?? '' }}',
                    ssl_certificate: '{{ $security->ssl_certificate ?? '' }}',
                    ssl_expiry: '{{ $security->ssl_expiry ?? '' }}',
                    auth_method: '{{ $security->auth_method ?? '2fa' }}',
                    encryption_algorithm: '{{ $security->encryption_algorithm ?? 'AES-256' }}',
                    access_level: '{{ $security->access_level ?? 'private' }}',
                    monitoring_interval: '{{ $security->monitoring_interval ?? 5 }}',
                    threshold_value: '{{ $security->threshold_value ?? 100 }}',
                    threshold_type: '{{ $security->threshold_type ?? 'attempts' }}',
                    alert_enabled: {{ $security->alert_enabled ?? true }},
                    alert_threshold: '{{ $security->alert_threshold ?? 10 }}',
                    auto_block: {{ $security->auto_block ?? false }},
                    block_duration: '{{ $security->block_duration ?? 30 }}',
                    description: '{{ $security->description ?? '' }}',
                    notes: '{{ $security->notes ?? '' }}',
                    allowed_ips: '{{ $security->allowed_ips ?? '' }}',
                    blocked_ips: '{{ $security->blocked_ips ?? '' }}',
                    geo_restriction: '{{ $security->geo_restriction ?? 'none' }}',
                    allowed_countries: '{{ $security->allowed_countries ?? '' }}',
                    log_level: '{{ $security->log_level ?? 'info' }}',
                    retention_days: '{{ $security->retention_days ?? 30 }}',
                    backup_enabled: {{ $security->backup_enabled ?? true }},
                    backup_frequency: '{{ $security->backup_frequency ?? 'daily' }}'
                },
                
                init() {
                    // Initialize form with security data
                },
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    // Submit form normally
                    event.target.submit();
                }
            }
        }
    </script>
@endsection

