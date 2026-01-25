@extends('layouts.app')

@section('header')
    <h2 class="text-3xl font-bold text-gradient mb-2">إنشاء إعداد أمان جديد</h2>
    <p class="text-gray-600 dark:text-gray-400">أدخل معلومات إعداد الأمان الجديد لإضافته إلى النظام</p>
@endsection

@section('content')
    <div x-data="securityForm()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">إنشاء إعداد أمان جديد</h1>
                    <p class="text-purple-100">املأ النموذج التالي لإضافة إعداد أمان جديد إلى نظام المراقبة</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('security.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf
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
                                            x-model="form.type"
                                            class="form-input">
                                        <option value="firewall">جدار الحماية</option>
                                        <option value="ssl">شهادة SSL</option>
                                        <option value="authentication">المصادقة</option>
                                        <option value="encryption">التشفير</option>
                                        <option value="access_control">التحكم في الوصول</option>
                                        <option value="monitoring">مراقبة الأمان</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            x-model="form.status"
                                            class="form-input">
                                        <option value="active">نشط</option>
                                        <option value="inactive">غير نشط</option>
                                        <option value="warning">تحذير</option>
                                        <option value="critical">حرج</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الأولوية
                                    </label>
                                    <select name="priority" id="priority"
                                            x-model="form.priority"
                                            class="form-input">
                                        <option value="low">منخفضة</option>
                                        <option value="medium">متوسطة</option>
                                        <option value="high">عالية</option>
                                        <option value="critical">حرجة</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العميل
                                    </label>
                                    <select name="client_id" id="client_id"
                                            x-model="form.client_id"
                                            class="form-input">
                                        <option value="">اختر العميل</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
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
                                        <option value="2fa">المصادقة الثنائية</option>
                                        <option value="oauth">OAuth</option>
                                        <option value="ldap">LDAP</option>
                                        <option value="saml">SAML</option>
                                        <option value="jwt">JWT</option>
                                    </select>
                                </div>

                                <div x-show="form.type === 'encryption'">
                                    <label for="encryption_algorithm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        خوارزمية التشفير
                                    </label>
                                    <select name="encryption_algorithm" id="encryption_algorithm"
                                            x-model="form.encryption_algorithm"
                                            class="form-input">
                                        <option value="AES-256">AES-256</option>
                                        <option value="AES-128">AES-128</option>
                                        <option value="RSA-2048">RSA-2048</option>
                                        <option value="RSA-4096">RSA-4096</option>
                                        <option value="SHA-256">SHA-256</option>
                                    </select>
                                </div>

                                <div x-show="form.type === 'access_control'">
                                    <label for="access_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مستوى الوصول
                                    </label>
                                    <select name="access_level" id="access_level"
                                            x-model="form.access_level"
                                            class="form-input">
                                        <option value="public">عام</option>
                                        <option value="private">خاص</option>
                                        <option value="restricted">مقيد</option>
                                        <option value="admin">مدير</option>
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
                                        <option value="attempts">عدد المحاولات</option>
                                        <option value="time">الوقت</option>
                                        <option value="percentage">النسبة المئوية</option>
                                        <option value="count">العدد</option>
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
                                        <option value="none">بدون قيود</option>
                                        <option value="whitelist">القائمة البيضاء</option>
                                        <option value="blacklist">القائمة السوداء</option>
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
                                        <option value="debug">تصحيح</option>
                                        <option value="info">معلومات</option>
                                        <option value="warning">تحذير</option>
                                        <option value="error">خطأ</option>
                                        <option value="critical">حرج</option>
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
                                        <option value="daily">يومي</option>
                                        <option value="weekly">أسبوعي</option>
                                        <option value="monthly">شهري</option>
                                        <option value="yearly">سنوي</option>
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
                        <span x-show="!loading">إنشاء الإعداد</span>
                        <span x-show="loading">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 8 8 0 018 0z"></path>
                            </svg>
                            جاري الإنشاء...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function securityForm() {
            return {
                loading: false,
                errors: {},
                form: {
                    name: '',
                    type: 'firewall',
                    status: 'active',
                    priority: 'medium',
                    client_id: '',
                    firewall_rules: '',
                    ssl_certificate: '',
                    ssl_expiry: '',
                    auth_method: '2fa',
                    encryption_algorithm: 'AES-256',
                    access_level: 'private',
                    monitoring_interval: 5,
                    threshold_value: 100,
                    threshold_type: 'attempts',
                    alert_enabled: true,
                    alert_threshold: 10,
                    auto_block: false,
                    block_duration: 30,
                    description: '',
                    notes: '',
                    allowed_ips: '',
                    blocked_ips: '',
                    geo_restriction: 'none',
                    allowed_countries: '',
                    log_level: 'info',
                    retention_days: 30,
                    backup_enabled: true,
                    backup_frequency: 'daily'
                },
                
                init() {
                    // Initialize form with old data if exists
                    @if(old())
                        this.form = @json(old());
                    @endif
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

