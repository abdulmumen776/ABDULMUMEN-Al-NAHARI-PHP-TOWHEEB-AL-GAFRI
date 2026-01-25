@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">إضافة عميل جديد</h2>
            <p class="text-gray-600 dark:text-gray-400">أدخل معلومات العميل الجديد لإضافته إلى النظام</p>
        </div>
        <a href="{{ route('clients.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="clientForm()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'العملاء', 'url' => route('clients.index')],
            ['title' => 'إضافة عميل جديد']
        ]" />
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">معلومات أساسية</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">البيانات الرئيسية للعميل</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-blue-600 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">معلومات الاتصال</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">بيانات التواصل</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-gray-300 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الإعدادات</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">تفضيلات إضافية</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('clients.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form Area (2 columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 100-14 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">معلومات أساسية</h3>
                            </div>
                            
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
                                        error="{{ $errors->first('name') ?? '' }}"
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
                                        <option value="active">نشط</option>
                                        <option value="inactive">غير نشط</option>
                                        <option value="suspended">محظور</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-green-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">معلومات الاتصال</h3>
                            </div>
                            
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
                                        icon="M3 5a2 2 0 012-2h3.28a1 1 0 01.447-.894l9.425 9.425a1 1 0 01.894.447L10.828 15H16a2 2 0 002-2v-3.28a1 1 0 00-.447-.894L6.169 3.837A1 1 0 015.753 3.837z"
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
                                        x-model="form.contact_phone"
                                        icon="M3 5a2 2 0 012-2h3.28a1 1 0 01.447-.894l9.425 9.425a1 1 0 01.894.447L10.828 15H16a2 2 0 002-2v-3.28a1 1 0 00-.447-.894L6.169 3.837A1 1 0 015.753 3.837z"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-purple-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 110 2h.01M12 10a2 2 0 110 2h.01M12 14a2 2 0 110 2h.01M12 18a2 2 0 110 2h.01"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">معلومات إضافية</h3>
                            </div>
                            
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
                                        icon="M21 12a9 9 0 011-9 9 9 0 0119 0z"
                                    />
                                </div>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العنوان
                                    </label>
                                    <textarea name="address" id="address" rows="3"
                                              x-model="form.address"
                                              placeholder="أدخل العنوان الكامل للعميل"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        ملاحظات
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              x-model="form.notes"
                                              placeholder="أدخل أي ملاحظات إضافية عن العميل"
                                              class="form-input resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (1 column) -->
                    <div class="space-y-6">
                        <!-- Live Preview -->
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-white sticky top-4">
                            <h3 class="text-lg font-semibold mb-4">معاينة العميل</h3>
                            <div class="space-y-4">
                                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                            <span class="text-xl font-bold">ع</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-lg">اسم العميل</p>
                                            <p class="text-blue-100">لا يوجد صناعة</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm">الحالة:</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium"
                                              :class="{
                                                  'bg-green-100 text-green-800': form.status === 'active',
                                                  'bg-gray-100 text-gray-800': form.status === 'inactive',
                                                  'bg-red-100 text-red-800': form.status === 'suspended'
                                              }"
                                              x-text="form.status === 'active' ? 'نشط' : form.status === 'inactive' ? 'غير نشط' : 'محظور'"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm">التنبيهات:</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium"
                                              :class="{
                                                  'bg-green-100 text-green-800': form.notifications,
                                                  'bg-gray-100 text-gray-800': !form.notifications
                                              }"
                                              x-text="form.notifications ? 'مفعلة' : 'معطلة'"></span>
                                    </div>
                                </div>
                                
                                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                    <h4 class="font-semibold mb-2">معلومات الاتصال</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"></path>
                                            </svg>
                                            <span x-text="form.contact_email || 'لا يوجد بريد'"></span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.447-.894l9.425 9.425a1 1 0 01.894.447L10.828 15H16a2 2 0 002-2v-3.28a1 1 0 00-.447-.894L6.169 3.837A1 1 0 015.753 3.837z"
                                            </svg>
                                            <span x-text="form.contact_phone || 'لا يوجد هاتف'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Panel -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإعدادات</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التنبيهات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">استقبل تلقي التنبيهات لهذا العميل</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.notifications" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">المراقبة التلقائية</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل المراقبة التلقائية للعمليات</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_monitoring" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">حدود العمليات</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">الحد الأقصى للعمليات</p>
                                        </div>
                                    </div>
                                    <input type="number" x-model="form.operation_limit" min="1" max="100"
                                           class="form-input text-center w-full">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('clients.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <div class="flex space-x-3">
                        <button type="button" @click="resetForm()" class="btn btn-outline">
                            إعادة تعيين
                        </button>
                        <button type="submit" :disabled="loading" class="btn btn-primary">
                            <span x-show="!loading">حفظ العميل</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 100 8 8 0 00-8 0z"></path>
                                </svg>
                                جاري الحفظ...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function clientForm() {
            return {
                loading: false,
                errors: {},
                form: {
                    name: '',
                    industry: '',
                    contact_email: '',
                    contact_phone: '',
                    status: 'active',
                    website: '',
                    address: '',
                    notes: '',
                    notifications: false,
                    auto_monitoring: false,
                    operation_limit: 10
                },
                
                init() {
                    // Initialize form with default values
                },
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    // Submit form normally
                    event.target.submit();
                },
                
                resetForm() {
                    this.form = {
                        name: '',
                        industry: '',
                        contact_email: '',
                        contact_phone: '',
                        status: 'active',
                        website: '',
                        address: '',
                        notes: '',
                        notifications: false,
                        auto_monitoring: false,
                        operation_limit: 10
                    };
                    this.errors = {};
                },
                
                saveDraft() {
                    // Save form data to localStorage
                    localStorage.setItem('clientDraft', JSON.stringify(this.form));
                    this.$root.showNotification('تم حفظ المسودة', 'success');
                }
            }
        }
    </script>
@endsection

