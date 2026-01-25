@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">إضافة API جديد</h2>
            <p class="text-gray-600 dark:text-gray-400">أدخل معلومات الـ API الجديد لإضافته إلى النظام</p>
        </div>
        <a href="{{ route('apis.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="apiForm()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-200">إضافة API جديد</h1>
                    <p class="text-gray-600 dark:text-gray-400">أدخل معلومات الـ API الجديد لإضافته إلى النظام</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-blue-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success') && session('created_api'))
            @php
                $createdApi = session('created_api');
                $statusLabels = [
                    'monitored' => 'مراقب',
                    'inactive' => 'غير نشط',
                    'error' => 'خطأ',
                ];
                $statusText = $statusLabels[$createdApi['status'] ?? 'monitored'] ?? 'مراقب';
            @endphp
            <div class="card card-success p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">تم إنشاء الـ API بنجاح</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">هذه هي التفاصيل التي تم حفظها في قاعدة البيانات.</p>
                    </div>
                    <span class="badge badge-success">نجاح</span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الاسم</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $createdApi['name'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الرابط الأساسي</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $createdApi['base_url'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الحالة</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $statusText }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">معرّف الـ API</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">#{{ $createdApi['id'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">معرّف العميل</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $createdApi['client_id'] ?? '—' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الرسالة</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ session('success') }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">النتيجة بصيغة JSON</p>
                    <pre class="text-xs leading-relaxed bg-gray-900 text-gray-100 rounded-lg p-4 overflow-auto">{{ json_encode([
                        'success' => true,
                        'api' => $createdApi,
                        'message' => 'API created successfully',
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg p-8" :class="darkMode ? 'bg-gray-800' : ''">
            <form id="apiForm" action="{{ route('apis.store') }}" method="POST">
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
                                        اسم الـ API <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required
                                        placeholder="أدخل اسم الـ API"
                                        icon="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
                                </div>

                                <div>
                                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العميل <span class="text-red-500">*</span>
                                    </label>
                                    <select name="client_id" id="client_id" required
                                            x-model="form.client_id"
                                            class="form-input">
                                        <option value="">اختر العميل</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            x-model="form.status"
                                            class="form-input">
                                        <option value="monitored">مراقب</option>
                                        <option value="active">نشط</option>
                                        <option value="inactive">غير نشط</option>
                                        <option value="error">خطأ</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="version" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الإصدار
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="version" 
                                        id="version"
                                        placeholder="مثال: v1.0.0"
                                        icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Endpoint Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات النقطة النهائية</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="base_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الرابط الأساسي <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="base_url" 
                                        id="base_url"
                                        placeholder="مثال: https://api.example.com" 
                                        required
                                        placeholder="https://api.example.com"
                                        icon="M21 12a9 9 0 011-9 9 9 9 0 0119 9z"
                                    />
                                </div>

                                <div>
                                    <label for="endpoint" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المسار <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="endpoint" 
                                        id="endpoint"
                                        placeholder="مثال: /users" 
                                        required
                                        placeholder="/api/v1/users"
                                        icon="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الطريقة الافتراضية
                                    </label>
                                    <select name="method" id="method"
                                            x-model="form.method"
                                            class="form-input">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                        <option value="PATCH">PATCH</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المهلة الزمنية (بالثواني)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="timeout" 
                                        id="timeout"
                                        placeholder="30"
                                        model="form.timeout"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Authentication -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">المصادقة والأمان</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="auth_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع المصادقة
                                    </label>
                                    <select name="auth_type" id="auth_type"
                                            x-model="form.auth_type"
                                            class="form-input">
                                        <option value="none">بدون مصادقة</option>
                                        <option value="bearer">Bearer Token</option>
                                        <option value="basic">Basic Auth</option>
                                        <option value="api_key">API Key</option>
                                        <option value="oauth2">OAuth 2.0</option>
                                    </select>
                                </div>

                                <div x-show="form.auth_type === 'bearer' || form.auth_type === 'api_key'">
                                    <label for="auth_token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التوكن
                                    </label>
                                    <x-input 
                                        type="password" 
                                        name="auth_token" 
                                        id="auth_token"
                                        placeholder="أدخل رمز المصادقة"
                                        placeholder="أدخل التوكن"
                                        model="form.auth_token"
                                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div x-show="form.auth_type === 'basic'">
                                    <label for="auth_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم المستخدم
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="auth_username" 
                                        id="auth_username"
                                        placeholder="أدخل اسم المستخدم"
                                        placeholder="أدخل اسم المستخدم"
                                        model="form.auth_username"
                                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 100-14 7 7 0 0114 0z"
                                    />
                                </div>

                                <div x-show="form.auth_type === 'basic'">
                                    <label for="auth_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        كلمة المرور
                                    </label>
                                    <x-input 
                                        type="password" 
                                        name="auth_password" 
                                        id="auth_password"
                                        placeholder="أدخل كلمة المرور"
                                        placeholder="أدخل كلمة المرور"
                                        model="form.auth_password"
                                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div x-show="form.auth_type === 'oauth2'">
                                    <label for="oauth_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Client ID
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="oauth_client_id" 
                                        id="oauth_client_id"
                                        placeholder="أدخل معرف العميل"
                                        placeholder="أدخل Client ID"
                                        model="form.oauth_client_id"
                                        icon="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2 5h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14"
                                    />
                                </div>

                                <div x-show="form.auth_type === 'oauth2'">
                                    <label for="oauth_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Client Secret
                                    </label>
                                    <x-input 
                                        type="password" 
                                        name="oauth_client_secret" 
                                        id="oauth_client_secret"
                                        placeholder="أدخل السر السري للعميل"
                                        placeholder="أدخل Client Secret"
                                        model="form.oauth_client_secret"
                                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Monitoring Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات المراقبة</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="monitoring_interval" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        فترة المراقبة (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="monitoring_interval" 
                                        id="monitoring_interval"
                                        placeholder="بالدقائق"
                                        placeholder="5"
                                        model="form.monitoring_interval"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="retry_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عدد المحاولات
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="retry_count" 
                                        id="retry_count"
                                        placeholder="عدد المحاولات"
                                        placeholder="3"
                                        model="form.retry_count"
                                        icon="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </div>

                                <div>
                                    <label for="success_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة النجاح (ms)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="success_threshold" 
                                        id="success_threshold"
                                        placeholder=
                                        placeholder="1000"
                                        model="form.success_threshold"
                                        icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="error_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة الخطأ (ms)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="error_threshold" 
                                        id="error_threshold"
                                        placeholder=
                                        placeholder="5000"
                                        model="form.error_threshold"
                                        icon="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Headers -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الرؤوس الافتراضية</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="headers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الرؤوس (Headers)
                                    </label>
                                    <textarea name="headers" id="headers" rows="4"
                                              x-model="form.headers"
                                              placeholder='أدخل الرؤوس المخصصة (JSON)'
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الوصف والملاحظات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وصف الـ API
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً مفصلاً للـ API"
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

                        <!-- Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات المتقدمة</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">المراقبة التلقائية</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل المراقبة التلقائية للـ API</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_monitoring" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التنبيهات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">استلام تنبيهات عند فشل الـ API</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.notifications" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">تسجيل الأداء</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">حفظ بيانات الأداء</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.log_performance" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">الاختبارات الصحية</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">إجراء اختبارات صحية دورية</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.health_checks" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('apis.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">إضافة الـ API</span>
                        <span x-show="loading">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 8 8 0 018 0z"></path>
                            </svg>
                            جاري الإضافة...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function apiForm() {
            return {
                darkMode: false,
                loading: false,
                errors: {},
                form: {
                    client_id: '',
                    name: '',
                    base_url: '',
                    endpoint: '',
                    method: 'GET',
                    owner: '',
                    description: '',
                    status: 'monitored',
                    auth_type: 'none',
                    auth_token: '',
                    auth_username: '',
                    auth_password: '',
                    oauth_client_id: '',
                    oauth_client_secret: '',
                    monitoring_interval: 5,
                    retry_count: 3,
                    success_threshold: 1000,
                    error_threshold: 5000,
                    headers: '{\n    "Content-Type": "application/json",\n    "Accept": "application/json"\n}',
                    auto_monitoring: true,
                    notifications: true,
                    log_performance: true,
                    health_checks: true
                },
                
                submitForm(event) {
                    event.preventDefault();
                    this.loading = true;
                    
                    // Submit the form normally
                    const form = document.getElementById('apiForm');
                    const formData = new FormData(form);
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.success) {
                            window.location.href = '{{ route('apis.index') }}/' + data.api.id + '/success';
                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                this.errors = data.errors;
                            }
                            this.loading = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.loading = false;
                        // If there's an error, try submitting the form normally
                        form.submit();
                    });
                },
                
                init() {
                    // Initialize form with any existing data
                    @if(old())
                        @foreach(old() as $key => $value)
                            if (this.form.hasOwnProperty('{{ $key }}')) {
                                this.form['{{ $key }}'] = {!! is_array($value) ? json_encode($value) : "'" . addslashes($value) . "'" !!};
                            }
                        @endforeach
                    @endif
                    
                    // Check for dark mode
                    this.darkMode = document.documentElement.classList.contains('dark') || 
                                 (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches));
                }
            }
        }
    </script>
@endsection







