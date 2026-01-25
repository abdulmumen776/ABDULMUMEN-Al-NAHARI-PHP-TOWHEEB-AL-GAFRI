@extends('layouts.app')

@section('header')
    <h2 class="text-3xl font-bold text-gradient mb-2">إنشاء توكن API جديد</h2>
    <p class="text-gray-600 dark:text-gray-400">أدخل معلومات توكن API الجديد لإضافته إلى النظام</p>
@endsection

@section('content')
    <div x-data="tokenForm()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-green-600 to-teal-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">إنشاء توكن API جديد</h1>
                    <p class="text-green-100">املأ النموذج التالي لإضافة توكن API جديد إلى نظام المراقبة</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17M9 17v2a2 2 0 002 2h10a2 2 0 002-2v-2M9 17l3-3m3 3l3-3"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('tokens.store') }}" method="POST" @submit.prevent="submitForm">
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
                                        اسم التوكن <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required
                                        placeholder="أدخل اسم التوكن"
                                        icon="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17"
                                    />
                                </div>

                                <div>
                                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العميل <span class="text-red-500">*</span>
                                    </label>
                                    <select name="client_id" id="client_id" required
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
                                            class="form-input">
                                        <option value="active">نشط</option>
                                        <option value="inactive">غير نشط</option>
                                        <option value="expired">منتهي</option>
                                        <option value="revoked">ملغي</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع التوكن
                                    </label>
                                    <select name="type" id="type"
                                            class="form-input">
                                        <option value="api">API</option>
                                        <option value="web">Web</option>
                                        <option value="mobile">Mobile</option>
                                        <option value="service">Service</option>
                                        <option value="integration">Integration</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الغرض من التوكن
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="purpose" 
                                        id="purpose"
                                        placeholder="المراقبة، التكامل، الوصول إلى البيانات"
                                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Token Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" >
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات التوكن</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التوكن
                                    </label>
                                    <div class="flex space-x-2">
                                        <div class="flex-grow relative">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17"></path>
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="token" 
                                                id="token"
                                                class="form-input pr-10"
                                                placeholder="سيتم إنشاء التوكن تلقائياً"
                                            />
                                        </div>
                                        <button type="button" class="btn btn-outline">
                                            إنشاء تلقائي
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تاريخ الانتهاء
                                    </label>
                                    <x-input 
                                        type="datetime-local" 
                                        name="expires_at" 
                                        id="expires_at"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>

                                <div>
                                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        حد الاستخدام (عدد الطلبات)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="usage_limit" 
                                        id="usage_limit"
                                        placeholder="1000"
                                        icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </div>

                                <div>
                                    <label for="rate_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        حد المعدل (طلبات/دقيقة)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="rate_limit" 
                                        id="rate_limit"
                                        placeholder="100"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="auto_refresh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التحديث التلقائي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="auto_refresh" value="0">
                                        <input type="checkbox" name="auto_refresh" value="1" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div>
                                    <label for="refresh_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        فترة التحديث (بالأيام)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="refresh_days" 
                                        id="refresh_days"
                                        placeholder="30"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Permissions -->
                        <div class="bg-white rounded-xl shadow-lg p-6" >
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الصلاحيات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الصلاحيات الممنوحة
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="read" {{ in_array('read', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">قراءة البيانات</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="write" {{ in_array('write', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">كتابة البيانات</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="delete" {{ in_array('delete', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">حذف البيانات</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="admin" {{ in_array('admin', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">صلاحيات المدير</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="monitor" {{ in_array('monitor', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">المراقبة</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="permissions[]" value="analytics" {{ in_array('analytics', old('permissions', [])) ? 'checked' : '' }} class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">التحليلات</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="allowed_endpoints" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        النقاط النهائية المسموح بها
                                    </label>
                                    <textarea name="allowed_endpoints" id="allowed_endpoints" rows="3"
                                              placeholder="/api/v1/*, /api/v2/users/*"
                                              class="form-input resize-none font-mono text-sm">{{ old('allowed_endpoints') }}</textarea>
                                </div>

                                <div>
                                    <label for="restricted_endpoints" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        النقاط النهائية المقيدة
                                    </label>
                                    <textarea name="restricted_endpoints" id="restricted_endpoints" rows="3"
                                              placeholder="/api/v1/admin/*, /api/v1/delete/*"
                                              class="form-input resize-none font-mono text-sm">{{ old('restricted_endpoints') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- IP Restrictions -->
                        <div class="bg-white rounded-xl shadow-lg p-6" >
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">قيود IP</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="ip_restriction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تقيود IP
                                    </label>
                                    <select name="ip_restriction" id="ip_restriction"
                                            class="form-input">
                                        <option value="none">بدون قيود</option>
                                        <option value="whitelist">القائمة البيضاء</option>
                                        <option value="blacklist">القائمة السوداء</option>
                                    </select>
                                </div>

                                <div>
                                <div x-show="form.ip_restriction !== 'none'">
                                    <label for="allowed_ips" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عناوين IP المسموح بها/المحظورة
                                    </label>
                                    <textarea name="allowed_ips" id="allowed_ips" rows="3"
                                              placeholder="192.168.1.0/24, 10.0.0.0/8"
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="geo_restriction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        القيود الجغرافية
                                    </label>
                                    <select name="geo_restriction" id="geo_restriction"
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
                                        icon="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"
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
                                        وصف التوكن
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              placeholder="اترك فارغاً للإنشاء التلقائي"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ملاحظات إضافية
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              placeholder="أدخل أي ملاحظات إضافية"
                                              value="{{ old('notes') }}"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الوسوم
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="tags" 
                                        id="tags"
                                        placeholder="مهم, إنتاجي, تكامل"
                                        icon="M7 7h.01M7 3h.01"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات الإشعارات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="notification_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        البريد الإلكتروني للإشعارات
                                    </label>
                                    <x-input 
                                        type="email" 
                                        name="notification_email" 
                                        id="notification_email"
                                        placeholder="admin@example.com"
                                        icon="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"
                                    />
                                </div>

                                <div>
                                    <label for="alert_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عتبة التنبيه (% من الاستخدام)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="alert_threshold" 
                                        id="alert_threshold"
                                        placeholder="80"
                                        icon="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="expiry_notification" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        إشعار قبل انتهاء الصلاحية (بالأيام)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="expiry_notification" 
                                        id="expiry_notification"
                                        placeholder="7"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رابط Webhook للإشعارات
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="webhook_url" 
                                        id="webhook_url"
                                        placeholder="https://example.com/webhook"
                                        icon="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('tokens.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">إنشاء التوكن</span>
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

    <!-- Success Modal -->
    <div x-show="showTokenModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showTokenModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                تم إنشاء التوكن بنجاح!
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    تم إنشاء توكن API جديد بنجاح. يمكنك نسخ التوكن من الحقل أدناه.
                                </p>
                                <div class="mt-3 p-3 bg-gray-100 rounded-md">
                                    <p class="text-xs font-mono text-gray-700 break-all" x-text="generatedToken"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="showTokenModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        موافق
                    </button>
                    <button type="button" 
                            @click="copyTokenToClipboard()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        نسخ التوكن
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showErrorModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                خطأ
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="errorMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="showErrorModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        موافق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tokenForm() {
            return {
                loading: false,
                errors: {},
                showTokenModal: false,
                showErrorModal: false,
                generatedToken: '',
                errorMessage: '',
                form: {
                    name: '',
                    client_id: '',
                    status: 'active',
                    type: 'api',
                    purpose: '',
                    token: '',
                    expires_at: '',
                    usage_limit: 1000,
                    rate_limit: 100,
                    auto_refresh: false,
                    refresh_days: 30,
                    permissions: ['read'],
                    allowed_endpoints: '/api/v1/*',
                    restricted_endpoints: '/api/v1/admin/*',
                    ip_restriction: 'none',
                    allowed_ips: '',
                    geo_restriction: 'none',
                    allowed_countries: '',
                    description: '',
                    notes: '',
                    tags: '',
                    notification_email: '',
                    alert_threshold: 80,
                    expiry_notification: 7,
                    webhook_url: ''
                },
                
                init() {
                    // Initialize form with old data if exists
                    @if(old())
                        this.form = @json(old());
                    @endif
                },
                
                generateToken() {
                    try {
                        // Generate a professional API token format: prefix_random_suffix
                        const prefix = 'tok';
                        let randomPart = '';
                        
                        // Try using crypto API if available, otherwise fallback to Math.random
                        if (window.crypto && window.crypto.getRandomValues) {
                            randomPart = Array.from(crypto.getRandomValues(new Uint8Array(16)), byte => byte.toString(16).padStart(2, '0')).join('');
                        } else {
                            // Fallback for older browsers
                            randomPart = Math.random().toString(36).substring(2, 18) + Math.random().toString(36).substring(2, 18);
                        }
                        
                        const timestamp = Date.now().toString(36);
                        const token = `${prefix}_${randomPart}_${timestamp}`;
                        
                        this.form.token = token;
                        
                        // Show modal popup instead of alert
                        this.showTokenModal = true;
                        this.generatedToken = token;
                        
                        console.log('Token generated:', token); // Debug log
                    } catch (error) {
                        console.error('Error generating token:', error);
                        this.errorMessage = 'حدث خطأ أثناء إنشاء التوكن';
                        this.showErrorModal = true;
                    }
                },
                
                copyTokenToClipboard() {
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(this.generatedToken).then(() => {
                            this.showTokenModal = false;
                            if (this.$root && this.$root.showNotification) {
                                this.$root.showNotification('تم نسخ التوكن إلى الحافظة', 'success');
                            }
                        }).catch(err => {
                            console.error('Failed to copy token: ', err);
                            this.fallbackCopyTokenToClipboard();
                        });
                    } else {
                        this.fallbackCopyTokenToClipboard();
                    }
                },
                
                fallbackCopyTokenToClipboard() {
                    const textArea = document.createElement("textarea");
                    textArea.value = this.generatedToken;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        document.execCommand('copy');
                        this.showTokenModal = false;
                        if (this.$root && this.$root.showNotification) {
                            this.$root.showNotification('تم نسخ التوكن إلى الحافظة', 'success');
                        }
                    } catch (err) {
                        console.error('Fallback: Oops, unable to copy', err);
                    }
                    
                    document.body.removeChild(textArea);
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

