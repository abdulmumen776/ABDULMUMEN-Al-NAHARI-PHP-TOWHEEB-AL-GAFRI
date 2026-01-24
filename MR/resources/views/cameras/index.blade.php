@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">الكاميرات</h2>
            <p class="text-gray-600 dark:text-gray-400">إدارة كاميرات المراقبة والتحكم فيها</p>
        </div>
        <a href="{{ route('cameras.create') }}" class="btn btn-primary">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة كاميرا جديدة
            </span>
        </a>
    </div>
@endsection

@section('content')
    <div x-data="camerasSystem()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'المراقبة', 'url' => route('monitoring.index')],
            ['title' => 'الكاميرات', 'url' => route('cameras.index')],
            ['title' => 'قائمة الكاميرات']
        ]" />

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Cameras -->
            <div class="card p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">إجمالي الكاميرات</p>
                        <p class="text-3xl font-bold">24</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Cameras -->
            <div class="card p-6 bg-gradient-to-r from-green-500 to-teal-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">الكاميرات النشطة</p>
                        <p class="text-3xl font-bold">18</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Offline Cameras -->
            <div class="card p-6 bg-gradient-to-r from-red-500 to-orange-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm">الكاميرات غير متصلة</p>
                        <p class="text-3xl font-bold">3</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="card p-6 bg-gradient-to-r from-yellow-500 to-amber-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm">تحت الصيانة</p>
                        <p class="text-3xl font-bold">3</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إجراءات سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <button @click="startAllCameras()" class="flex items-center p-4 bg-green-50 dark:bg-gray-800 rounded-lg hover:bg-green-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-green-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">تشغيل الكل</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تفعيل جميع الكاميرات</p>
                    </div>
                </button>

                <button @click="stopAllCameras()" class="flex items-center p-4 bg-red-50 dark:bg-gray-800 rounded-lg hover:bg-red-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-red-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">إيقاف الكل</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">إيقاف جميع الكاميرات</p>
                    </div>
                </button>

                <button @click="checkStatus()" class="flex items-center p-4 bg-blue-50 dark:bg-gray-800 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-blue-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">فحص الحالة</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تحديث حالة الكاميرات</p>
                    </div>
                </button>

                <button @click="exportData()" class="flex items-center p-4 bg-purple-50 dark:bg-gray-800 rounded-lg hover:bg-purple-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-purple-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">تصدير البيانات</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تصدير تقرير الكاميرات</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Cameras Grid -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">الكاميرات</h3>
                <div class="flex space-x-2">
                    <select x-model="filterStatus" @change="filterCameras()" class="form-input">
                        <option value="all">جميع الكاميرات</option>
                        <option value="active">نشطة</option>
                        <option value="offline">غير متصلة</option>
                        <option value="maintenance">تحت الصيانة</option>
                    </select>
                    <button @click="refreshData()" class="btn btn-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        تحديث
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Camera Card 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://picsum.photos/seed/camera1/400/250.jpg" alt="Camera Feed" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">نشط</span>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">1080p</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">كاميرا #001</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">المدخل الرئيسي</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">IP: 192.168.1.101</span>
                            <div class="flex space-x-1">
                                <button @click="viewCamera(1)" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="editCamera(1)" class="text-yellow-600 hover:text-yellow-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteCamera(1)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Card 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://picsum.photos/seed/camera2/400/250.jpg" alt="Camera Feed" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">نشط</span>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">4K</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">كاميرا #002</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">مواقف السيارات</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">IP: 192.168.1.102</span>
                            <div class="flex space-x-1">
                                <button @click="viewCamera(2)" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="editCamera(2)" class="text-yellow-600 hover:text-yellow-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteCamera(2)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Card 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://picsum.photos/seed/camera3/400/250.jpg" alt="Camera Feed" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-full">غير متصلة</span>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">1080p</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">كاميرا #003</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">المستودع</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">IP: 192.168.1.103</span>
                            <div class="flex space-x-1">
                                <button @click="viewCamera(3)" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="editCamera(3)" class="text-yellow-600 hover:text-yellow-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteCamera(3)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camera Card 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="https://picsum.photos/seed/camera4/400/250.jpg" alt="Camera Feed" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">تحت الصيانة</span>
                        </div>
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">720p</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">كاميرا #004</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">الردهة</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">IP: 192.168.1.104</span>
                            <div class="flex space-x-1">
                                <button @click="viewCamera(4)" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button @click="editCamera(4)" class="text-yellow-600 hover:text-yellow-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteCamera(4)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function camerasSystem() {
            return {
                loading: false,
                cameras: [],
                filterStatus: 'all',
                
                init() {
                    this.loadCameras();
                },
                
                async loadCameras() {
                    this.loading = true;
                    try {
                        // Load cameras data
                        this.cameras = await fetch('/api/cameras').then(res => res.json());
                    } catch (error) {
                        console.error('Failed to load cameras:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                startAllCameras() {
                    this.$root.showNotification('تم تشغيل جميع الكاميرات', 'success');
                },
                
                stopAllCameras() {
                    this.$root.showNotification('تم إيقاف جميع الكاميرات', 'success');
                },
                
                checkStatus() {
                    this.$root.showNotification('جاري فحص حالة الكاميرات...', 'info');
                    this.loadCameras();
                },
                
                exportData() {
                    this.$root.showNotification('جاري تصدير بيانات الكاميرات...', 'info');
                },
                
                refreshData() {
                    this.loadCameras();
                    this.$root.showNotification('تم تحديث بيانات الكاميرات', 'success');
                },
                
                filterCameras() {
                    // Filter cameras based on status
                    this.$root.showNotification('تم تطبيق الفلتر', 'success');
                },
                
                viewCamera(id) {
                    window.location.href = `/cameras/${id}`;
                },
                
                editCamera(id) {
                    window.location.href = `/cameras/${id}/edit`;
                },
                
                deleteCamera(id) {
                    if (confirm('هل أنت متأكد من حذف هذه الكاميرا؟')) {
                        this.$root.showNotification('تم حذف الكاميرا', 'success');
                    }
                }
            }
        }
    </script>
@endsection
