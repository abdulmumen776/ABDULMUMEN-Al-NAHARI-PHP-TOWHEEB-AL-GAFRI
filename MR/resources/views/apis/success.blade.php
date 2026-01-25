@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">تم إنشاء الـ API بنجاح</h2>
            <p class="text-gray-600 dark:text-gray-400">تمت إضافة الـ API الجديد بنجاح إلى النظام</p>
        </div>
        <a href="{{ route('apis.index') }}" class="btn btn-outline">
            العودة للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div class="card card-success p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">تم إنشاء الـ API بنجاح</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">تم حفظ الـ API الجديد في قاعدة البيانات</p>
            </div>
            <span class="badge badge-success">نجاح</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الاسم</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $api->name }}</p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الرابط الأساسي</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $api->base_url }}</p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">معرّف الـ API</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">#{{ $api->id }}</p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الحالة</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                    @if($api->status === 'monitored')
                        <span class="text-green-600 dark:text-green-400">مراقب</span>
                    @elseif($api->status === 'inactive')
                        <span class="text-yellow-600 dark:text-yellow-400">غير نشط</span>
                    @else
                        <span class="text-red-600 dark:text-red-400">خطأ</span>
                    @endif
                </p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">تاريخ الإنشاء</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                    {{ $api->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/70">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">آخر تحديث</p>
                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                    {{ $api->updated_at->format('Y-m-d H:i') }}
                </p>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a href="{{ route('apis.edit', $api) }}" class="btn btn-primary">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل الـ API
            </a>
            <a href="{{ route('apis.index') }}" class="btn btn-outline">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة إلى قائمة الـ APIs
            </a>
            <a href="{{ route('apis.create') }}" class="btn btn-outline">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة API جديد
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الخطوات التالية</h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <span class="text-blue-600 dark:text-blue-300 font-bold">1</span>
                </div>
                <div class="mr-3">
                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">إضافة النقاط النهائية</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        قم بإضافة النقاط النهائية (Endpoints) لـ API الخاص بك وتهيئة إعداداتها.
                    </p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <span class="text-blue-600 dark:text-blue-300 font-bold">2</span>
                </div>
                <div class="mr-3">
                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">اختبار الاتصال</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        تأكد من أن الـ API يعمل بشكل صحيح من خلال إجراء اختبار اتصال.
                    </p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <span class="text-blue-600 dark:text-blue-300 font-bold">3</span>
                </div>
                <div class="mr-3">
                    <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">إعداد المراقبة</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        قم بتهيئة إعدادات المراقبة لضمان تتبع أداء الـ API الخاص بك.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
