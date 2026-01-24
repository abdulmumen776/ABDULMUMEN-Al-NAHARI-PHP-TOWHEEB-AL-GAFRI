@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">إضافة كاميرا جديدة</h2>
            <p class="text-gray-600 dark:text-gray-400">إعداد كاميرا مراقبة جديدة للنظام</p>
        </div>
        <a href="{{ route('cameras.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="cameraForm()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'المراقبة', 'url' => route('monitoring.index')],
            ['title' => 'الكاميرات', 'url' => route('cameras.index')],
            ['title' => 'إضافة كاميرا جديدة']
        ]" />
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">معلومات أساسية</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">تفاصيل الكاميرا</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-blue-600 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">إعدادات الاتصال</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">تكوين الشبكة</p>
                    </div>
                </div>
                <div class="flex-1 mx-8">
                    <div class="h-2 bg-gray-300 rounded-full"></div>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">الإعدادات المتقدمة</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500">جودة وتسجيل</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card p-8">
            <form action="{{ route('cameras.store') }}" method="POST" @submit.prevent="submitForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form Area (2 columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">معلومات أساسية</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="camera_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم الكاميرا <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="camera_name" 
                                        id="camera_name" 
                                        required
                                        placeholder="أدخل اسم الكاميرا"
                                        model="form.camera_name"
                                        error="{{ $errors->first('camera_name') ?? '' }}"
                                        icon="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
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
                                        <option value="1">المبنى الرئيسي - الطابق الأول</option>
                                        <option value="2">المبنى الرئيسي - الطابق الثاني</option>
                                        <option value="3">مواقف السيارات</option>
                                        <option value="4">المدخل الرئيسي</option>
                                        <option value="5">المستودع</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الوصف
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              x-model="form.description"
                                              placeholder="أدخل وصفاً للكاميرا"
                                              class="form-input resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Settings -->
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-green-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 3.904-10.237 0-14.142s-10.237 3.905-14.142 0 3.905-3.905 10.237 0 14.142z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">إعدادات الاتصال</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="ip_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            عنوان IP <span class="text-red-500">*</span>
                                        </label>
                                        <x-input 
                                            type="text" 
                                            name="ip_address" 
                                            id="ip_address" 
                                            required
                                            placeholder="192.168.1.100"
                                            model="form.ip_address"
                                            error="{{ $errors->first('ip_address') ?? '' }}"
                                            icon="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 3.904-10.237 0-14.142s-10.237 3.905-14.142 0 3.905-3.905 10.237 0 14.142z"
                                        />
                                    </div>

                                    <div>
                                        <label for="port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            المنفذ <span class="text-red-500">*</span>
                                        </label>
                                        <x-input 
                                            type="number" 
                                            name="port" 
                                            id="port" 
                                            required
                                            placeholder="8080"
                                            model="form.port"
                                            error="{{ $errors->first('port') ?? '' }}"
                                            icon="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 3.904-10.237 0-14.142s-10.237 3.905-14.142 0 3.905-3.905 10.237 0 14.142z"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        اسم المستخدم
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="username" 
                                        id="username" 
                                        placeholder="admin"
                                        model="form.username"
                                        error="{{ $errors->first('username') ?? '' }}"
                                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 100-14 7 7 0 0114 0z"
                                    />
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        كلمة المرور
                                    </label>
                                    <x-input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        placeholder="••••••••"
                                        model="form.password"
                                        error="{{ $errors->first('password') ?? '' }}"
                                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">الاتصال التلقائي</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">اختبار الاتصال عند الحفظ</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_connect" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-purple-200 dark:border-gray-600">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">الإعدادات المتقدمة</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="resolution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            جودة الفيديو <span class="text-red-500">*</span>
                                        </label>
                                        <select name="resolution" id="resolution" required
                                                x-model="form.resolution"
                                                class="form-input">
                                            <option value="720p">720p (HD)</option>
                                            <option value="1080p">1080p (Full HD)</option>
                                            <option value="4K">4K (Ultra HD)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="fps" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            معدل الإطارات (FPS)
                                        </label>
                                        <select name="fps" id="fps"
                                                x-model="form.fps"
                                                class="form-input">
                                            <option value="15">15 FPS</option>
                                            <option value="25">25 FPS</option>
                                            <option value="30">30 FPS</option>
                                            <option value="60">60 FPS</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="recording_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مسار التسجيل
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="recording_path" 
                                        id="recording_path" 
                                        placeholder="/recordings/cameras/"
                                        model="form.recording_path"
                                        error="{{ $errors->first('recording_path') ?? '' }}"
                                        icon="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                    />
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التسجيل المستمر</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">تسجيل الفيديو بشكل مستمر</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="form.continuous_recording" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">الكشف عن الحركة</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل الكشف عن الحركة</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="form.motion_detection" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">التسجيل الصوتي</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">تسجيل الصوت مع الفيديو</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" x-model="form.audio_recording" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
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
                            <h3 class="text-lg font-semibold mb-4">معاينة الكاميرا</h3>
                            <div class="space-y-4">
                                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                    <div class="flex items-center mb-3">
                                        <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-lg" x-text="form.camera_name || 'اسم الكاميرا'"></p>
                                            <p class="text-blue-100">كاميرا جديدة</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm">المكان:</span>
                                            <span class="text-sm" x-text="getLocationName(form.location_id)"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الجودة:</span>
                                            <span class="text-sm" x-text="form.resolution || '---'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الـ IP:</span>
                                            <span class="text-sm" x-text="form.ip_address || '---.---.---.---'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm">الحالة:</span>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">غير متصلة</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Test -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">اختبار الاتصال</h3>
                            
                            <div class="space-y-4">
                                <button @click="testConnection()" :disabled="testing" class="w-full btn btn-outline">
                                    <span x-show="!testing">اختبار الاتصال</span>
                                    <span x-show="testing" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 100 8 8 8 0 00-8 0z"></path>
                                        </svg>
                                        جاري الاختبار...
                                    </span>
                                </button>

                                <div x-show="connectionResult" class="p-3 rounded-lg" :class="connectionResult.success ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'">
                                    <p class="text-sm font-medium" x-text="connectionResult.message"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Settings -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات سريعة</h3>
                            
                            <div class="space-y-4">
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">حفظ التسجيلات</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">مدة الحفظ بالأيام</p>
                                        </div>
                                    </div>
                                    <input type="number" x-model="form.retention_days" min="1" max="365"
                                           class="form-input w-full" placeholder="30">
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">حجم الملف الأقصى</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">بالـ MB</p>
                                        </div>
                                    </div>
                                    <input type="number" x-model="form.max_file_size" min="10" max="1000"
                                           class="form-input w-full" placeholder="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('cameras.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <div class="flex space-x-3">
                        <button type="button" @click="resetForm()" class="btn btn-outline">
                            إعادة تعيين
                        </button>
                        <button type="submit" :disabled="loading" class="btn btn-primary">
                            <span x-show="!loading">إضافة الكاميرا</span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018 0 100 8 8 8 0 00-8 0z"></path>
                                </svg>
                                جاري الإضافة...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function cameraForm() {
            return {
                loading: false,
                testing: false,
                errors: {},
                connectionResult: null,
                form: {
                    camera_name: '',
                    location_id: '',
                    description: '',
                    ip_address: '',
                    port: '8080',
                    username: '',
                    password: '',
                    auto_connect: true,
                    resolution: '1080p',
                    fps: '30',
                    recording_path: '/recordings/cameras/',
                    continuous_recording: true,
                    motion_detection: true,
                    audio_recording: false,
                    retention_days: 30,
                    max_file_size: 100
                },
                
                init() {
                    // Initialize form with default values
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
                
                async testConnection() {
                    this.testing = true;
                    this.connectionResult = null;
                    
                    try {
                        // Simulate connection test
                        await new Promise(resolve => setTimeout(resolve, 2000));
                        
                        // Random success/failure for demo
                        const success = Math.random() > 0.3;
                        
                        this.connectionResult = {
                            success: success,
                            message: success ? 'تم الاتصال بالكاميرا بنجاح!' : 'فشل الاتصال بالكاميرا. يرجى التحقق من الإعدادات.'
                        };
                    } catch (error) {
                        this.connectionResult = {
                            success: false,
                            message: 'حدث خطأ أثناء اختبار الاتصال'
                        };
                    } finally {
                        this.testing = false;
                    }
                },
                
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    
                    // Submit form normally
                    event.target.submit();
                },
                
                resetForm() {
                    this.form = {
                        camera_name: '',
                        location_id: '',
                        description: '',
                        ip_address: '',
                        port: '8080',
                        username: '',
                        password: '',
                        auto_connect: true,
                        resolution: '1080p',
                        fps: '30',
                        recording_path: '/recordings/cameras/',
                        continuous_recording: true,
                        motion_detection: true,
                        audio_recording: false,
                        retention_days: 30,
                        max_file_size: 100
                    };
                    this.errors = {};
                    this.connectionResult = null;
                }
            }
        }
    </script>
@endsection
