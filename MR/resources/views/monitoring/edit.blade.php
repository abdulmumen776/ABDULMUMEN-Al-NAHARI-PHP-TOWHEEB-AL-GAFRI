@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تعديل جلسة المراقبة</h2>
            <p class="text-gray-600 dark:text-gray-400">تعديل إعدادات جلسة المراقبة الحالية</p>
        </div>
        <a href="{{ route('monitoring.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="monitoringForm()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'المراقبة', 'url' => route('monitoring.index')],
            ['title' => 'تعديل جلسة المراقبة']
        ]" />
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">معلومات أساسية</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">تفاصيل جلسة المراقبة</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-blue-600 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">اختيار الكاميرات</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">تحديد الكاميرات</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-blue-600 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الإعدادات</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">تفضيلات المراقبة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('monitoring.update', $monitoring->id) }}" method="POST" @submit.prevent="submitForm">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form Area (2 columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">معلومات أساسية</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="session_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم الجلسة <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="session_name" 
                                        id="session_name" 
                                        required
                                        value="{{ old('session_name', $monitoring->session_name) }}"
                                        placeholder="أدخل اسم جلسة المراقبة"
                                        model="form.session_name"
                                        error="{{ $errors->first('session_name') ?? '' }}"
                                        icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المكان <span class="text-red-500">*</span>
                                    </label>
                                    <select name="location_id" id="location_id" required
                                            x-model="form.location_id"
                                            class="form-input">
                                        <option value="">اختر المكان</option>
                                        <option value="1" {{ old('location_id', $monitoring->location_id) == '1' ? 'selected' : '' }}>المبنى الرئيسي - الطابق الأول</option>
                                        <option value="2" {{ old('location_id', $monitoring->location_id) == '2' ? 'selected' : '' }}>المبنى الرئيسي - الطابق الثاني</option>
                                        <option value="3" {{ old('location_id', $monitoring->location_id) == '3' ? 'selected' : '' }}>مواقف السيارات</option>
                                        <option value="4" {{ old('location_id', $monitoring->location_id) == '4' ? 'selected' : '' }}>المدخل الرئيسي</option>
                                        <option value="5" {{ old('location_id', $monitoring->location_id) == '5' ? 'selected' : '' }}>المستودع</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            x-model="form.status"
                                            class="form-input">
                                        <option value="active" {{ old('status', $monitoring->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $monitoring->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="paused" {{ old('status', $monitoring->status) == 'paused' ? 'selected' : '' }}>متوقف مؤقتاً</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الوصف
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً لجلسة المراقبة"
                                              class="form-input resize-none">{{ old('description', $monitoring->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Selection -->
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-green-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">اختيار الكاميرات</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                         @click="toggleCamera(1)"
                                         :class="{'border-blue-500 bg-blue-50 dark:bg-blue-900': form.cameras.includes(1)}">
                                        <div class="flex items-center">
                                            <input type="checkbox" x-model="form.cameras" value="1" class="sr-only">
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">كاميرا #001</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">المدخل الرئيسي - 1080p</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                         @click="toggleCamera(2)"
                                         :class="{'border-blue-500 bg-blue-50 dark:bg-blue-900': form.cameras.includes(2)}">
                                        <div class="flex items-center">
                                            <input type="checkbox" x-model="form.cameras" value="2" class="sr-only">
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">كاميرا #002</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">الموقف - 4K</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                         @click="toggleCamera(3)"
                                         :class="{'border-blue-500 bg-blue-50 dark:bg-blue-900': form.cameras.includes(3)}">
                                        <div class="flex items-center">
                                            <input type="checkbox" x-model="form.cameras" value="3" class="sr-only">
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">كاميرا #003</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">المستودع - 1080p</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                         @click="toggleCamera(4)"
                                         :class="{'border-blue-500 bg-blue-50 dark:bg-blue-900': form.cameras.includes(4)}">
                                        <div class="flex items-center">
                                            <input type="checkbox" x-model="form.cameras" value="4" class="sr-only">
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 dark:text-gray-200">كاميرا #004</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">الردهة - 1080p</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Settings -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-purple-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">جدول المراقبة</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            وقت البدء <span class="text-red-500">*</span>
                                        </label>
                                        <x-input 
                                            type="time" 
                                            name="start_time" 
                                            id="start_time" 
                                            required
                                            value="{{ old('start_time', $monitoring->start_time) }}"
                                            model="form.start_time"
                                            error="{{ $errors->first('start_time') ?? '' }}"
                                            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </div>

                                    <div>
                                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            وقت الانتهاء <span class="text-red-500">*</span>
                                        </label>
                                        <x-input 
                                            type="time" 
                                            name="end_time" 
                                            id="end_time" 
                                            required
                                            value="{{ old('end_time', $monitoring->end_time) }}"
                                            model="form.end_time"
                                            error="{{ $errors->first('end_time') ?? '' }}"
                                            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="recording_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        أيام التسجيل
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="saturday" class="ml-2">
                                            <span class="text-sm">السبت</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="sunday" class="ml-2">
                                            <span class="text-sm">الأحد</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="monday" class="ml-2">
                                            <span class="text-sm">الإثنين</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="tuesday" class="ml-2">
                                            <span class="text-sm">الثلاثاء</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="wednesday" class="ml-2">
                                            <span class="text-sm">الأربعاء</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="thursday" class="ml-2">
                                            <span class="text-sm">الخميس</span>
                                        </label>
                                        <label class="flex items-center p-2 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" x-model="form.recording_days" value="friday" class="ml-2">
                                            <span class="text-sm">الجمعة</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (1 column) -->
                    <div class="space-y-6">
                        <!-- Live Preview -->
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-white sticky top-4">
                            <h3 class="text-lg font-semibold mb-4">معاينة الجلسة</h3>
                            <div class="space-y-4">
                                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-lg" x-text="form.session_name || 'اسم الجلسة'"></p>
                                            <p class="text-blue-100">جلسة مراقبة</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm">المكان:</span>
                                            <span class="text-sm" x-text="getLocationName(form.location_id)"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الكاميرات:</span>
                                            <span class="text-sm" x-text="form.cameras.length + ' كاميرات'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الوقت:</span>
                                            <span class="text-sm" x-text="(form.start_time || '--:--') + ' - ' + (form.end_time || '--:--')"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الحالة:</span>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-green-100 text-green-800': form.status === 'active',
                                                      'bg-gray-100 text-gray-800': form.status === 'inactive',
                                                      'bg-yellow-100 text-yellow-800': form.status === 'paused'
                                                  }"
                                                  x-text="getStatusText(form.status)"></span>
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
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التسجيل التلقائي</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">بدء التسجيل تلقائياً</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_record" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التنبيهات</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل التنبيهات</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.enable_alerts" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">جودة التسجيل</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">جودة الفيديو</p>
                                        </div>
                                    </div>
                                    <select x-model="form.recording_quality" class="form-input w-full">
                                        <option value="720p">720p</option>
                                        <option value="1080p">1080p</option>
                                        <option value="4K">4K</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('monitoring.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <div class="flex space-x-3">
                        <button type="button" @click="resetForm()" class="btn btn-outline">
                            إعادة تعيين
                        </button>
                        <button type="submit" :disabled="loading" class="btn btn-primary">
                            <span x-show="!loading">حفظ التغييرات</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 100 8 8 8 0 00-8 0z"></path>
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
        function monitoringForm() {
            return {
                loading: false,
                errors: {},
                form: {
                    session_name: '{{ $monitoring->session_name }}',
                    location_id: '{{ $monitoring->location_id }}',
                    status: '{{ $monitoring->status }}',
                    description: '{{ $monitoring->description }}',
                    cameras: @json($monitoring->cameras ?? []),
                    start_time: '{{ $monitoring->start_time }}',
                    end_time: '{{ $monitoring->end_time }}',
                    recording_days: @json($monitoring->recording_days ?? []),
                    auto_record: {{ $monitoring->auto_record ? 'true' : 'false' }},
                    enable_alerts: {{ $monitoring->enable_alerts ? 'true' : 'false' }},
                    recording_quality: '{{ $monitoring->recording_quality }}'
                },
                
                init() {
                    // Initialize form with existing data
                },
                
                toggleCamera(id) {
                    const index = this.form.cameras.indexOf(id);
                    if (index > -1) {
                        this.form.cameras.splice(index, 1);
                    } else {
                        this.form.cameras.push(id);
                    }
                },
                
                getLocationName(id) {
                    const locations = {
                        1: 'المبنى الرئيسي - الطابق الأول',
                        2: 'المبنى الرئيسي - الطابق الثاني',
                        3: 'مواقف السيارات',
                        4: 'المدخل الرئيسي',
                        5: 'المستودع'
                    };
                    return locations[id] || 'لم يتم الاختيار';
                },
                
                getStatusText(status) {
                    const statusTexts = {
                        'active': 'نشط',
                        'inactive': 'غير نشط',
                        'paused': 'متوقف مؤقتاً'
                    };
                    return statusTexts[status] || status;
                },
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    // Submit form normally
                    event.target.submit();
                },
                
                resetForm() {
                    // Reset to original values
                    location.reload();
                }
            }
        }
    </script>
@endsection
