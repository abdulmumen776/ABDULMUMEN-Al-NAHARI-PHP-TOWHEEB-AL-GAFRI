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
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
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
                                        model="form.name"
                                        :error="errors.name"
                                        icon="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17"
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
                                            x-model="form.type"
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
                                        model="form.purpose"
                                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Token Settings -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات التوكن</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التوكن
                                    </label>
                                    <div class="flex space-x-2">
                                        <x-input 
                                            type="text" 
                                            name="token" 
                                            id="token"
                                            placeholder="اترك فارغاً للإنشاء التلقائي"
                                            model="form.token"
                                            icon="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17"
                                        />
                                        <button type="button" @click="generateToken()" class="btn btn-outline">
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
                                        model="form.expires_at"
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
                                        model="form.usage_limit"
                                        icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002 2zm0 0V9a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012-2z"
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
                                        model="form.rate_limit"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="auto_refresh" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التحديث التلقائي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_refresh" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.auto_refresh">
                                    <label for="refresh_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        فترة التحديث (بالأيام)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="refresh_days" 
                                        id="refresh_days"
                                        placeholder="30"
                                        model="form.refresh_days"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Permissions -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الصلاحيات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الصلاحيات الممنوحة
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="read" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">قراءة البيانات</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="write" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">كتابة البيانات</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="delete" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">حذف البيانات</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="admin" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">صلاحيات المدير</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="monitor" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">المراقبة</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.permissions" value="analytics" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">التحليلات</span>
                                            </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="allowed_endpoints" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        النقاط النهائية المسموح بها
                                    </label>
                                    <textarea name="allowed_endpoints" id="allowed_endpoints" rows="3"
                                              x-model="form.allowed_endpoints"
                                              placeholder="/api/v1/*, /api/v2/users/*"
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="restricted_endpoints" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        النقاط النهائية المقيدة
                                    </label>
                                    <textarea name="restricted_endpoints" id="restricted_endpoints" rows="3"
                                              x-model="form.restricted_endpoints"
                                              placeholder="/api/v1/admin/*, /api/v1/delete/*"
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- IP Restrictions -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">قيود IP</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="ip_restriction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تقيود IP
                                    </label>
                                    <select name="ip_restriction" id="ip_restriction"
                                            x-model="form.ip_restriction"
                                            class="form-input">
                                        <option value="none">بدون قيود</option>
                                        <option value="whitelist">القائمة البيضاء</option>
                                        <option value="blacklist">القائمة السوداء</option>
                                    </select>
                                </div>

                                <div x-show="form.ip_restriction !== 'none'">
                                    <label for="allowed_ips" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عناوين IP المسموح بها/المحظورة
                                    </label>
                                    <textarea name="allowed_ips" id="allowed_ips" rows="3"
                                              x-model="form.allowed_ips"
                                              placeholder="192.168.1.0/24, 10.0.0.0/8"
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

                        <!-- Description -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الوصف والملاحظات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وصف التوكن
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً مفصلاً للتوكن"
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

                                <div>
                                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الوسوم
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="tags" 
                                        id="tags"
                                        placeholder="مهم, إنتاجي, تكامل"
                                        model="form.tags"
                                        icon="M7 7h.01M7 3h.01"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
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
                                        model="form.notification_email"
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
                                        model="form.alert_threshold"
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
                                        model="form.expiry_notification"
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
                                        model="form.webhook_url"
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

    <script>
        function tokenForm() {
            return {
                loading: false,
                errors: {},
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
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    let token = '';
                    for (let i = 0; i < 32; i++) {
                        token += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.form.token = token;
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
