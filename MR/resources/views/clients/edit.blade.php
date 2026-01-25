@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تعديل العميل</h2>
            <p class="text-gray-600 dark:text-gray-400">تحديث معلومات العميل في النظام</p>
        </div>
        <a href="{{ route('clients.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="clientEdit()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'العملاء', 'url' => route('clients.index')],
            ['title' => 'تعديل العميل']
        ]" />
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">تعديل العميل</h1>
                    <p class="text-blue-100">تحديث معلومات العميل الحالي في النظام</p>
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
            <form action="{{ route('clients.update', $client) }}" method="POST" @submit.prevent="submitForm">
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
                                        اسم العميل <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="name" 
                                        id="name" 
                                        required
                                        placeholder="أدخل اسم العميل"
                                        :value="{{ old('name', $client->name) }}"
                                        :error="errors.name"
                                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 100-14 7 7 0 0114 0z"
                                    />
                                </div>

                                <div>
                                    <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الصناعة
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="industry" 
                                        id="industry"
                                        placeholder="أدخل الصناعة"
                                        :value="{{ old('industry', $client->industry) }}"
                                        icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1"
                                    />
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            x-model="form.status"
                                            class="form-input">
                                        <option value="active" x-text="form.status === 'active' ? 'selected' : ''">نشط</option>
                                        <option value="inactive" x-text="form.status === 'inactive' ? 'selected' : ''">غير نشط</option>
                                        <option value="suspended" x-text="form.status === 'suspended' ? 'selected' : ''">محظور</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات الاتصال</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        البريد الإلكتروني
                                    </label>
                                    <x-input 
                                        type="email" 
                                        name="contact_email" 
                                        id="contact_email"
                                        placeholder="example@email.com"
                                        :value="{{ old('contact_email', $client->contact_email) }}"
                                        icon="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"
                                    />
                                </div>

                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رقم الهاتف
                                    </label>
                                    <x-input 
                                        type="tel" 
                                        name="contact_phone" 
                                        id="contact_phone"
                                        placeholder="+966 50 123 4567"
                                        :value="{{ old('contact_phone', $client->contact_phone) }}"
                                        :value="{{ old('contact_phone', $client->contact_phone) }}"
                                        icon="M3 5a2 2 0 012-2h3.28a1 1 0 01.447-.894l9.425 9.425a1 1 0 01.894.447L10.828 15H16a2 2 0 002-2v-3.28a1 1 0 00-.447-.894L6.169 3.837A1 1 0 015.753 3.837z"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Additional Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات إضافية</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الموقع الإلكتروني
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="website" 
                                        id="website"
                                        placeholder="https://example.com"
                                        :value="{{ old('website', $client->website) }}"
                                        icon="M21 12a9 9 0 011-9 9 9 9 0 0119 9z"
                                    />
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العنوان
                                    </label>
                                    <textarea name="address" id="address" rows="3"
                                              x-model="form.address"
                                              placeholder="أدخل العنوان الكامل"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ملاحظات
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
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التنبيهات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">استقبل تلقي التنبيهات لهذا العميل</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.notifications" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">المراقبة التلقائية</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل المراقبة التلقائية للعمليات</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_monitoring" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التقاريرات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">حدد عدد العمليات المسموح بها</p>
                                    </div>
                                    <input type="number" x-model="form.operation_limit" min="1" max="100"
                                           class="form-input w-20 text-center">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold mb-2">معاينة العميل</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center ml-3">
                                        <span class="text-lg font-bold">{{ substr($client->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">{{ $client->name ?? 'اسم العميل' }}</h4>
                                        <p class="text-sm text-blue-100">{{ $client->industry ?? 'الصناعة' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm">
                                    <span class="badge" :class="getStatusClass(form.status)" x-text="getStatusText(form.status)"></span>
                                    <span x-show="form.contact_email" x-text="form.contact_email"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('clients.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">تحديث العميل</span>
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
        function clientEdit() {
            return {
                loading: false,
                errors: {},
                form: {
                    name: '{{ $client->name }}',
                    industry: '{{ $client->industry }}',
                    contact_email: '{{ $client->contact_email }}',
                    contact_phone: '{{ $client->contact_phone }}',
                    status: '{{ $client->status }}',
                    website: '{{ $client->website ?? '' }}',
                    address: '{{ $client->address ?? '' }}',
                    notes: '{{ $client->notes ?? '' }}',
                    notifications: {{ $client->notifications ?? true }},
                    auto_monitoring: '{{ $client->auto_monitoring ?? false }}',
                    operation_limit: '{{ $client->operation_limit ?? 10 }}'
                },
                
                init() {
                    // Initialize form with client data
                },
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    // Submit form normally
                    event.target.submit();
                },
                
                getStatusClass(status) {
                    switch(status) {
                        case 'active': return 'bg-green-100 text-green-800';
                        case 'inactive': return 'bg-gray-100 text-gray-800';
                        case 'suspended': return 'bg-red-100 text-red-800';
                        default: return 'bg-gray-100 text-gray-800';
                    }
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'inactive': return 'غير نشط';
                        case 'suspended': return 'محظور';
                        default: return status;
                    }
                }
            }
        }
    </script>
@endsection

