@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تعديل التنبيه</h2>
            <p class="text-gray-600 dark:text-gray-400">تحديث معلومات التنبيه في النظام</p>
        </div>
        <a href="{{ route('alerts.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div x-data="alertEdit()" x-init="init()" x-cloak>
        <!-- Header Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-orange-500 to-red-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">تعديل التنبيه</h1>
                    <p class="text-orange-100">تحديث معلومات التنبيه الحالي في النظام</p>
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
            <form action="{{ route('alerts.update', $alert) }}" method="POST" @submit.prevent="submitForm">
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
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        عنوان التنبيه <span class="text-red-500">*</span>
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="title" 
                                        id="title" 
                                        required
                                        placeholder="أدخل عنوان التنبيه"
                                        model="form.title"
                                        :error="errors.title"
                                        icon="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رسالة التنبيه <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="message" id="message" rows="3" required
                                              x-model="form.message"
                                              placeholder="أدخل رسالة التنبيه"
                                              class="form-input resize-none"></textarea>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع التنبيه <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type" required
                                            x-model="form.type"
                                            class="form-input">
                                        <option value="info" {{ $alert->type == 'info' ? 'selected' : '' }}>معلومات</option>
                                        <option value="warning" {{ $alert->type == 'warning' ? 'selected' : '' }}>تحذير</option>
                                        <option value="error" {{ $alert->type == 'error' ? 'selected' : '' }}>خطأ</option>
                                        <option value="success" {{ $alert->type == 'success' ? 'selected' : '' }}>نجاح</option>
                                        <option value="critical" {{ $alert->type == 'critical' ? 'selected' : '' }}>حرج</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الأولوية
                                    </label>
                                    <select name="priority" id="priority"
                                            x-model="form.priority"
                                            class="form-input">
                                        <option value="low" {{ $alert->priority == 'low' ? 'selected' : '' }}>منخفضة</option>
                                        <option value="medium" {{ $alert->priority == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                        <option value="high" {{ $alert->priority == 'high' ? 'selected' : '' }}>عالية</option>
                                        <option value="critical" {{ $alert->priority == 'critical' ? 'selected' : '' }}>حرجة</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الحالة
                                    </label>
                                    <select name="status" id="status"
                                            x-model="form.status"
                                            class="form-input">
                                        <option value="active" {{ $alert->status == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                                        <option value="dismissed" {{ $alert->status == 'dismissed' ? 'selected' : '' }}>مجاهل</option>
                                        <option value="escalated" {{ $alert->status == 'escalated' ? 'selected' : '' }}>مُصعّد</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المصدر
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="source" 
                                        id="source"
                                        placeholder="نظام المراقبة"
                                        model="form.source"
                                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Target Information -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات الهدف</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="target_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        نوع الهدف
                                    </label>
                                    <select name="target_type" id="target_type"
                                            x-model="form.target_type"
                                            class="form-input">
                                        <option value="user" {{ $alert->target_type == 'user' ? 'selected' : '' }}>مستخدم</option>
                                        <option value="client" {{ $alert->target_type == 'client' ? 'selected' : '' }}>عميل</option>
                                        <option value="operation" {{ $alert->target_type == 'operation' ? 'selected' : '' }}>عملية</option>
                                        <option value="api" {{ $alert->target_type == 'api' ? 'selected' : '' }}>API</option>
                                        <option value="system" {{ $alert->target_type == 'system' ? 'selected' : '' }}>نظام</option>
                                        <option value="all" {{ $alert->target_type == 'all' ? 'selected' : '' }}>الجميع</option>
                                    </select>
                                </div>

                                <div x-show="form.target_type === 'user'">
                                    <label for="target_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        المستخدم المستهدف
                                    </label>
                                    <select name="target_id" id="target_id"
                                            x-model="form.target_id"
                                            class="form-input">
                                        <option value="">اختر المستخدم</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $alert->target_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="form.target_type === 'client'">
                                    <label for="target_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العميل المستهدف
                                    </label>
                                    <select name="target_id" id="target_id"
                                            x-model="form.target_id"
                                            class="form-input">
                                        <option value="">اختر العميل</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ $alert->target_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="form.target_type === 'operation'">
                                    <label for="target_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        العملية المستهدفة
                                    </label>
                                    <select name="target_id" id="target_id"
                                            x-model="form.target_id"
                                            class="form-input">
                                        <option value="">اختر العملية</option>
                                        @foreach($operations as $operation)
                                            <option value="{{ $operation->id }}" {{ $alert->target_id == $operation->id ? 'selected' : '' }}>{{ $operation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="form.target_type === 'api'">
                                    <label for="target_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الـ API المستهدف
                                    </label>
                                    <select name="target_id" id="target_id"
                                            x-model="form.target_id"
                                            class="form-input">
                                        <option value="">اختر الـ API</option>
                                        @foreach($apis as $api)
                                            <option value="{{ $api->id }}" {{ $alert->target_id == $api->id ? 'selected' : '' }}>{{ $api->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="target_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        البريد الإلكتروني للهدف
                                    </label>
                                    <x-input 
                                        type="email" 
                                        name="target_email" 
                                        id="target_email"
                                        placeholder="example@email.com"
                                        model="form.target_email"
                                        icon="M3 8a3 3 0 013 3h1a3 3 0 013 3v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h1"
                                    />
                                    />
                                </div>

                                <div>
                                    <label for="target_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رقم هاتف الهدف
                                    </label>
                                    <x-input 
                                        type="tel" 
                                        name="target_phone" 
                                        id="target_phone"
                                        placeholder="+966 50 123 4567"
                                        model="form.target_phone"
                                        icon="M3 5a2 2 0 012-2h3.28a1 1 0 01.447-.894l9.425 9.425a1 1 0 01.894.447L10.828 15H16a2 2 0 002-2v-3.28a1 1 0 00-.447-.894L6.169 3.837A1 1 0 015.753 3.837z"
                                    />
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Trigger Conditions -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">شروط التفعيل</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="trigger_condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        شرط التفعيل
                                    </label>
                                    <select name="trigger_condition" id="trigger_condition"
                                            x-model="form.trigger_condition"
                                            class="form-input">
                                        <option value="manual" {{ $alert->trigger_condition == 'manual' ? 'selected' : '' }}>يدوي</option>
                                        <option value="automatic" {{ $alert->trigger_condition == 'automatic' ? 'selected' : '' }}>تلقائي</option>
                                        <option value="scheduled" {{ $alert->trigger_condition == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                                        <option value="threshold" {{ $alert->trigger_condition == 'threshold' ? 'selected' : '' }}>عند تجاوز العتبة</option>
                                        <option value="failure" {{ $alert->trigger_condition == 'failure' ? 'selected' : '' }}>عند الفشل</option>
                                    </select>
                                </div>

                                <div x-show="form.trigger_condition === 'scheduled'">
                                    <label for="trigger_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وقت التفعيل
                                    </label>
                                    <x-input 
                                        type="datetime-local" 
                                        name="trigger_at" 
                                        id="trigger_at"
                                        model="form.trigger_at"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                    />
                                </div>

                                <div x-show="form.trigger_condition === 'threshold'">
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
                                    />
                                </div>

                                <div x-show="form.trigger_condition === 'threshold'">
                                    <label for="threshold_metric" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        مقياس العتبة
                                    </label>
                                    <select name="threshold_metric" id="threshold_metric"
                                            x-model="form.threshold_metric"
                                            class="form-input">
                                        <option value="response_time" {{ $alert->threshold_metric == 'response_time' ? 'selected' : '' }}>وقت الاستجابة</option>
                                        <option value="error_rate" {{ $alert->threshold_metric == 'error_rate' ? 'selected' : '' }}>معدد الأخطاء</option>
                                        <option value="memory_usage" {{ $alert->threshold_metric == 'memory_usage' ? 'selected' : '' }}>استخدام الذاكرة</option>
                                        <option value="cpu_usage" {{ $alert->threshold_metric == 'cpu_usage' ? 'selected' : '' }}>استخدام المعالج</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Notification Settings -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إعدادات الإشعارات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="notification_channels" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        قنوات الإشعار
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.notification_channels" value="email" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">البريد الإلكتروني</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.notification_channels" value="sms" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">الرسائل النصية</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.notification_channels" value="push" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">الإشعارات الفورية</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.notification_channels" value="webhook" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Webhook</span>
                                            </label>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رابط Webhook
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="webhook_url" 
                                        id="webhook_url"
                                        placeholder="https://example.com/webhook"
                                        model="form.webhook_url"
                                        icon="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
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
                                    />
                                </div>

                                <div>
                                    <label for="escalation_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التصعيد التلقائي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.escalation_enabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.escalation_enabled">
                                    <label for="escalation_after" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التصعيد بعد (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="escalation_after" 
                                        id="escalation_after"
                                        placeholder="30"
                                        model="form.escalation_after"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الإجراءات</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="action_buttons" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        أزرار الإجراءات
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.action_buttons" value="dismiss" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">تجاهل</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.action_buttons" value="resolve" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">حل المشكلة</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.action_buttons" value="acknowledge" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">استلام</span>
                                            </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" x-model="form.action_buttons" value="redirect" class="ml-2 rounded text-gray-600 focus:ring-blue-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">إعادة توجيه</span>
                                            </label>
                                    </div>
                                </div>
                                </div>

                                <div>
                                    <label for="redirect_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        رابط إعادة التوجيه
                                    </label>
                                    <x-input 
                                        type="url" 
                                        name="redirect_url" 
                                        id="redirect_url"
                                        placeholder="https://example.com"
                                        model="form.redirect_url"
                                        icon="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    />
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">بيانات وصفية</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="metadata" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        البيانات الوصفية (JSON)
                                    </label>
                                    <textarea name="metadata" id="metadata" rows="4"
                                              x-model="form.metadata"
                                              placeholder='{"key": "value", "source": "system"}'
                                              class="form-input resize-none font-mono text-sm"></textarea>
                                </div>

                                <div>
                                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الوسوم
                                    </label>
                                    <x-input 
                                        type="text" 
                                        name="tags" 
                                        id="tags"
                                        placeholder="مهم, عاجل, نظام"
                                        model="form.tags"
                                        icon="M7 7h.01M7 3h.01"
                                    />
                                    />
                                </div>

                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        الفئة
                                    </label>
                                    <select name="category" id="category"
                                            x-model="form.category"
                                            class="form-input">
                                        <option value="system" {{ $alert->category == 'system' ? 'selected' : '' }}>نظام</option>
                                        <option value="performance" {{ $alert->category == 'performance' ? 'selected' : '' }}>أداء</option>
                                        <option value="security" {{ $alert->category == 'security' ? 'selected' : '' }}>أمان</option>
                                        <option value="availability" {{ $alert->category == 'availability' ? 'selected' : '' }}>توفر</option>
                                        <option value="business" {{ $alert->category == 'business' ? 'selected' : '' }}>أعمال</option>
                                        <option value="technical" {{ $alert->category == 'technical' ? 'selected' : '' }}>تقني</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الانتهاء</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        وقت الانتهاء
                                    </label>
                                    <x-input 
                                        type="datetime-local" 
                                        name="expires_at" 
                                        id="expires_at"
                                        model="form.expires_at"
                                        icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h12zM4 10h14M4 6h14"
                                    />
                                    />
                                </div>

                                <div>
                                    <label for="auto_dismiss" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التجاهل التلقائي
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="form.auto_dismiss" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500 rounded-full peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div x-show="form.auto_dismiss">
                                    <label for="dismiss_after" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        التجاهل بعد (بالدقائق)
                                    </label>
                                    <x-input 
                                        type="number" 
                                        name="dismiss_after" 
                                        id="dismiss_after"
                                        placeholder="60"
                                        model="form.dismiss_after"
                                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('alerts.index') }}" class="btn btn-outline">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary" :disabled="loading">
                        <span x-show="!loading">تحديث التنبيه</span>
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
        function alertEdit() {
            return {
                loading: false,
                errors: {},
                form: {
                    title: '{{ $alert->title }}',
                    message: '{{ $alert->message }}',
                    type: '{{ $alert->type }}',
                    priority: '{{ $alert->priority ?? 'medium' }}',
                    source: '{{ $alert->source ?? 'نظام المراقبة' }}',
                    target_type: '{{ $alert->target_type ?? 'user' }}',
                    target_id: '{{ $alert->target_id ?? '' }}',
                    target_email: '{{ $alert->target_email ?? '' }}',
                    target_phone: '{{ $alert->target_phone ?? '' }}',
                    trigger_condition: '{{ $alert->trigger_condition ?? 'manual' }}',
                    trigger_at: '{{ $alert->trigger_at ?? '' }}',
                    threshold_value: '{{ $alert->threshold_value ?? '' }}',
                    threshold_metric: '{{ $alert->threshold_metric ?? 'response_time' }}',
                    notification_channels: {{ $alert->notification_channels ?? ['email'] }}',
                    webhook_url: '{{ $alert->webhook_url ?? '' }}',
                    retry_count: '{{ $alert->retry_count ?? 3 }}',
                    escalation_enabled: {{ $alert->escalation_enabled ?? false }}',
                    escalation_after: '{{ $alert->escalation_after ?? 30 }}',
                    action_buttons: '{{ $alert->action_buttons ?? ['dismiss'] }}',
                    redirect_url: '{{ $alert->redirect_url ?? '' }}',
                    metadata: '{{ $alert->metadata ?? '' }}',
                    tags: '{{ $alert->tags ?? '' }}',
                    category: '{{ $alert->category ?? 'system' }}',
                    expires_at: '{{ $alert->expires_at ?? '' }}',
                    auto_dismiss: '{{ $alert->auto_dismiss ?? false }}',
                    dismiss_after: '{{ $alert->dismiss_after ?? 60 }}',
                    status: '{{ $alert->status ?? 'active' }}'
                },
                
                init() {
                    // Initialize form with alert data
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

