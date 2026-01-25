@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تعديل الـ API</h2>
            <p class="text-gray-600 dark:text-gray-400">تحديث معلومات الـ API في النظام</p>
        </div>
        <a href="{{ route('apis.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="apiEdit()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-200">تعديل الـ API</h1>
                    <p class="text-gray-600 dark:text-gray-400">تحديث معلومات الـ API الحالي في النظام</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-blue-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-blue-600 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2v-11a2 2 0 00-2-2H6a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11H6m3 0h18M9 11v6m0 4h.01M15 17H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success') && session('updated_api'))
            @php
                $updatedApi = session('updated_api');
                $statusLabels = [
                    'monitored' => 'مراقب',
                    'inactive' => 'غير نشط',
                    'error' => 'خطأ',
                ];
                $statusText = $statusLabels[$updatedApi['status'] ?? 'monitored'] ?? 'مراقب';
            @endphp
            <div class="card card-success p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">تم تحديث الـ API بنجاح</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">تم حفظ التعديلات في قاعدة البيانات.</p>
                    </div>
                    <span class="badge badge-success">نجاح</span>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الاسم</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $updatedApi['name'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الرابط الأساسي</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $updatedApi['base_url'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الحالة</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $statusText }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">معرّف الـ API</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">#{{ $updatedApi['id'] ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">معرّف العميل</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $updatedApi['client_id'] ?? '—' }}</p>
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
                        'api' => $updatedApi,
                        'message' => 'API updated successfully',
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg p-8" :class="darkMode ? 'bg-gray-800' : ''">
            <form action="{{ route('apis.update', $api) }}" method="POST" @submit.prevent="submitForm">
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
                                        اسم الـ API <span class="text-red-500">*</span>
                                    </label>
                                    <div>
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            value="{{ old('name', $api->name) }}"
                                            placeholder="أدخل اسم الـ API"
                                            required
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                        />
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                            <option value="{{ $client->id }}" {{ $api->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
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
                                        <option value="monitored" {{ $api->status == 'monitored' ? 'selected' : '' }}>مراقب</option>
                                        <option value="active" {{ $api->status == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ $api->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="error" {{ $api->status == 'error' ? 'selected' : '' }}>خطأ</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="version" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الإصدار
                                    </label>
                                    <div>
                                        <input
                                            type="text"
                                            name="version"
                                            id="version"
                                            value="{{ old('version', $api->version) }}"
                                            placeholder="مثال: v1.0.0"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('version') border-red-500 @enderror"
                                        />
                                        @error('version')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                    <div>
                                        <input
                                            type="url"
                                            name="base_url"
                                            id="base_url"
                                            value="{{ old('base_url', $api->base_url) }}"
                                            placeholder="مثال: https://api.example.com"
                                            required
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('base_url') border-red-500 @enderror"
                                        />
                                        @error('base_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="endpoint" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المسار <span class="text-red-500">*</span>
                                    </label>
                                    <div>
                                        <input
                                            type="text"
                                            name="endpoint"
                                            id="endpoint"
                                            value="{{ old('endpoint', $api->endpoint) }}"
                                            placeholder="مثال: /users"
                                            required
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('endpoint') border-red-500 @enderror"
                                        />
                                        @error('endpoint')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الطريقة الافتراضية
                                    </label>
                                    <select name="method" id="method"
                                            x-model="form.method"
                                            class="form-input">
                                        <option value="GET" {{ $api->method == 'GET' ? 'selected' : '' }}>GET</option>
                                        <option value="POST" {{ $api->method == 'POST' ? 'selected' : '' }}>POST</option>
                                        <option value="PUT" {{ $api->method == 'PUT' ? 'selected' : '' }}>PUT</option>
                                        <option value="DELETE" {{ $api->method == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                                        <option value="PATCH" {{ $api->method == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المهلة الزمنية (بالثواني)
                                    </label>
                                    <div>
                                        <input
                                            type="number"
                                            name="timeout"
                                            id="timeout"
                                            value="{{ old('timeout', $api->timeout) }}"
                                            placeholder="30"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('timeout') border-red-500 @enderror"
                                        />
                                        @error('timeout')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                        <option value="none" {{ $api->auth_type == 'none' ? 'selected' : '' }}>بدون مصادقة</option>
                                        <option value="bearer" {{ $api->auth_type == 'bearer' ? 'selected' : '' }}>Bearer Token</option>
                                        <option value="basic" {{ $api->auth_type == 'basic' ? 'selected' : '' }}>Basic Auth</option>
                                        <option value="api_key" {{ $api->auth_type == 'api_key' ? 'selected' : '' }}>API Key</option>
                                        <option value="oauth2" {{ $api->auth_type == 'oauth2' ? 'selected' : '' }}>OAuth 2.0</option>
                                    </select>
                                </div>

                                <div x-show="form.auth_type === 'bearer' || form.auth_type === 'api_key'">
                                    <label for="auth_token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التوكن
                                    </label>
                                    <div>
                                        <input
                                            type="password"
                                            name="auth_token"
                                            id="auth_token"
                                            value="{{ old('auth_token', $api->auth_token) }}"
                                            placeholder="أدخل التوكن"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('auth_token') border-red-500 @enderror"
                                        />
                                        @error('auth_token')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="form.auth_type === 'basic'">
                                    <label for="auth_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم المستخدم
                                    </label>
                                    <div>
                                        <input
                                            type="text"
                                            name="auth_username"
                                            id="auth_username"
                                            value="{{ old('auth_username', $api->auth_username) }}"
                                            placeholder="أدخل اسم المستخدم"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('auth_username') border-red-500 @enderror"
                                        />
                                        @error('auth_username')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="form.auth_type === 'basic'">
                                    <label for="auth_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        كلمة المرور
                                    </label>
                                    <div>
                                        <input
                                            type="password"
                                            name="auth_password"
                                            id="auth_password"
                                            placeholder="أدخل كلمة المرور"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('auth_password') border-red-500 @enderror"
                                        />
                                        @error('auth_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="form.auth_type === 'oauth2'">
                                    <label for="oauth_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Client ID
                                    </label>
                                    <div>
                                        <input
                                            type="text"
                                            name="oauth_client_id"
                                            id="oauth_client_id"
                                            value="{{ old('oauth_client_id', $api->oauth_client_id) }}"
                                            placeholder="أدخل Client ID"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('oauth_client_id') border-red-500 @enderror"
                                        />
                                        @error('oauth_client_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="form.auth_type === 'oauth2'">
                                    <label for="oauth_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Client Secret
                                    </label>
                                    <div>
                                        <input
                                            type="password"
                                            name="oauth_client_secret"
                                            id="oauth_client_secret"
                                            placeholder="أدخل السر السري للعميل"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('oauth_client_secret') border-red-500 @enderror"
                                        />
                                        @error('oauth_client_secret')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                                    <div>
                                        <input
                                            type="number"
                                            name="monitoring_interval"
                                            id="monitoring_interval"
                                            value="{{ old('monitoring_interval', $api->monitoring_interval) }}"
                                            placeholder="5"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('monitoring_interval') border-red-500 @enderror"
                                        />
                                        @error('monitoring_interval')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="retry_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عدد المحاولات
                                    </label>
                                    <div>
                                        <input
                                            type="number"
                                            name="retry_count"
                                            id="retry_count"
                                            value="{{ old('retry_count', $api->retry_count) }}"
                                            placeholder="3"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('retry_count') border-red-500 @enderror"
                                        />
                                        @error('retry_count')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="success_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة النجاح (ms)
                                    </label>
                                    <div>
                                        <input
                                            type="number"
                                            name="success_threshold"
                                            id="success_threshold"
                                            value="{{ old('success_threshold', $api->success_threshold) }}"
                                            placeholder="1000"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('success_threshold') border-red-500 @enderror"
                                        />
                                        @error('success_threshold')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="error_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة الخطأ (ms)
                                    </label>
                                    <div>
                                        <input
                                            type="number"
                                            name="error_threshold"
                                            id="error_threshold"
                                            value="{{ old('error_threshold', $api->error_threshold) }}"
                                            placeholder="5000"
                                            class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('error_threshold') border-red-500 @enderror"
                                        />
                                        @error('error_threshold')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
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
                        <span x-show="!loading">تحديث الـ API</span>
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
        function apiEdit() {
            return {
                darkMode: false,
                loading: false,
                errors: {!! json_encode($errors->toArray()) !!},
                form: {
                    name: '{{ old('name', $api->name) }}',
                    client_id: '{{ old('client_id', $api->client_id) }}',
                    status: '{{ old('status', $api->status) }}',
                    version: '{{ old('version', $api->version ?? 'v1.0.0') }}',
                    base_url: '{{ old('base_url', $api->base_url) }}',
                    endpoint: '{{ old('endpoint', $api->endpoint) }}',
                    method: '{{ old('method', $api->method ?? 'GET') }}',
                    timeout: '{{ old('timeout', $api->timeout ?? 30) }}',
                    auth_type: '{{ old('auth_type', $api->auth_type ?? 'none') }}',
                    auth_token: '{{ old('auth_token', $api->auth_token) }}',
                    auth_username: '{{ old('auth_username', $api->auth_username) }}',
                    auth_password: '{{ old('auth_password', $api->auth_password) }}',
                    oauth_client_id: '{{ old('oauth_client_id', $api->oauth_client_id) }}',
                    oauth_client_secret: '{{ old('oauth_client_secret', $api->oauth_client_secret) }}',
                    monitoring_interval: '{{ old('monitoring_interval', $api->monitoring_interval ?? 5) }}',
                    retry_count: '{{ old('retry_count', $api->retry_count ?? 3) }}',
                    success_threshold: '{{ old('success_threshold', $api->success_threshold ?? 1000) }}',
                    error_threshold: '{{ old('error_threshold', $api->error_threshold ?? 5000) }}',
                    headers: {!! json_encode(old('headers', $api->headers ?: [])) !!},
                    description: '{{ old('description', $api->description) }}',
                    notes: '{{ old('notes', $api->notes) }}',
                    auto_monitoring: {{ old('auto_monitoring', $api->auto_monitoring ? 'true' : 'false') }},
                    notifications: {{ old('notifications', $api->notifications ? 'true' : 'false') }},
                    log_performance: {{ old('log_performance', $api->log_performance ? 'true' : 'false') }},
                    health_checks: {{ old('health_checks', $api->health_checks ? 'true' : 'false') }}
                },

                init() {
                    // Initialize form with API data
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



