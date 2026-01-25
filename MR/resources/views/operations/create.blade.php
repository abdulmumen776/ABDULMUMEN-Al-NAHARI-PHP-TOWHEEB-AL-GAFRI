@extends('layouts.app')

@section('header')
    <h2 class="text-3xl font-bold text-gradient mb-2">إنشاء عملية جديدة</h2>
    <p class="text-gray-600 dark:text-gray-400">أدخل معلومات العملية الجديدة لإضافتها إلى النظام</p>
@endsection

@section('content')
    <div x-data="operationForm()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">إنشاء عملية جديدة</h1>
                    <p class="text-purple-100">املأ النموذج التالي لإضافة عملية جديدة إلى نظام المراقبة</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('operations.store') }}" method="POST" @submit.prevent="submitForm">
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
                                        اسم العملية <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required
                                        placeholder="أدخل اسم العملية"
                                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
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
                                        <option value="scheduled">مجدول</option>
                                        <option value="completed">مكتمل</option>
                                        <option value="cancelled">ملغي</option>
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
                            </div>
                        </div>

                        <!-- Schedule Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات الجدولة</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وقت التنفيذ المجدول
                                    </label>
                                    <x-input 
                                        type="datetime-local" 
                                        name="scheduled_at" 
                                        id="scheduled_at"
                                        model="form.scheduled_at"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </div>

                                <div>
                                    <label for="estimated_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المدة التقديرية (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="estimated_duration" 
                                        id="estimated_duration"
                                        placeholder="60"
                                        model="form.estimated_duration"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="recurring" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        تكرار العملية
                                    </label>
                                    <select name="recurring" id="recurring"
                                            x-model="form.recurring"
                                            class="form-input">
                                        <option value="none">بدون تكرار</option>
                                        <option value="daily">يومي</option>
                                        <option value="weekly">أسبوعي</option>
                                        <option value="monthly">شهري</option>
                                        <option value="yearly">سنوي</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Configuration -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات والتكوين</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="api_endpoint" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نقطة نهاية الـ API
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="api_endpoint" 
                                        id="api_endpoint"
                                        placeholder="https://api.example.com/endpoint"
                                        model="form.api_endpoint"
                                        icon="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
                                </div>

                                <div>
                                    <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        طريقة HTTP
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

                                <div>
                                    <label for="retry_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عدد المحاولات
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="retry_count" 
                                        id="retry_count"
                                        placeholder="3"
                                        model="form.retry_count"
                                        icon="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Headers and Parameters -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الرؤوس والمعلمات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="headers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الرؤوس (Headers)
                                    </label>
                                    <textarea name="headers" id="headers" rows="3"
                                              x-model="form.headers"
                                              placeholder='{"Authorization": "Bearer token", "Content-Type": "application/json"}'
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="parameters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المعلمات (Parameters)
                                    </label>
                                    <textarea name="parameters" id="parameters" rows="3"
                                              x-model="form.parameters"
                                              placeholder='{"param1": "value1", "param2": "value2"}'
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="payload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        البيانات (Payload)
                                    </label>
                                    <textarea name="payload" id="payload" rows="3"
                                              x-model="form.payload"
                                              placeholder='{"key": "value"}'
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
                                        وصف العملية
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً مفصلاً للعملية"
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
                                        <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل المراقبة التلقائية للعملية</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_monitoring" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التنبيهات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">استلام تنبيهات عند فشل العملية</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.notifications" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">تسجيل النتائج</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">حفظ نتائج التنفيذ</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.log_results" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('operations.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">إنشاء العملية</span>
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
        function operationForm() {
            return {
                loading: false,
                errors: {},
                form: {
                    name: '',
                    client_id: '',
                    status: 'active',
                    priority: 'medium',
                    scheduled_at: '',
                    estimated_duration: 60,
                    recurring: 'none',
                    api_endpoint: '',
                    method: 'GET',
                    timeout: 30,
                    retry_count: 3,
                    headers: '',
                    parameters: '',
                    payload: '',
                    description: '',
                    notes: '',
                    auto_monitoring: true,
                    notifications: true,
                    log_results: true
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

