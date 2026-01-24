@extends('layouts.app')

@php
$breadcrumbs = [
    ['title' => 'المراقبة', 'url' => route('monitoring.index')],
    ['title' => 'قائمة المراقبة']
];
@endphp

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gradient mb-2">المراقبة</h2>
            <p class="text-gray-600 dark:text-gray-400">إدارة أنظمة المراقبة والكاميرات والأماكن</p>
        </div>
        <a href="{{ route('monitoring.create') }}" class="btn btn-primary">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة مراقبة جديدة
            </span>
        </a>
    </div>
@endsection

@section('content')
    <div x-data="monitoringSystem()" x-init="init()" x-cloak>
        <!-- Breadcrumbs -->
        <x-breadcrumb :breadcrumbs="[
            ['title' => 'المراقبة', 'url' => route('monitoring.index')],
            ['title' => 'قائمة المراقبة']
        ]" />

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Cameras -->
            <div class="card p-6 bg-gradient-to-r from-green-500 to-teal-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">الكاميرات النشطة</p>
                        <p class="text-3xl font-bold">24</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Locations -->
            <div class="card p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">الأماكن</p>
                        <p class="text-3xl font-bold">12</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Monitoring -->
            <div class="card p-6 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">جلسات المراقبة</p>
                        <p class="text-3xl font-bold">8</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="card p-6 bg-gradient-to-r from-red-500 to-orange-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm">التنبيهات</p>
                        <p class="text-3xl font-bold">3</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إجراءات سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('cameras.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-gray-800 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-blue-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">الكاميرات</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">إدارة الكاميرات</p>
                    </div>
                </a>

                <a href="{{ route('locations.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-gray-800 rounded-lg hover:bg-green-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-green-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">الأماكن</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">إدارة الأماكن</p>
                    </div>
                </a>

                <a href="{{ route('schedules.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-gray-800 rounded-lg hover:bg-purple-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-purple-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">الأوقات</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">جدول المراقبة</p>
                    </div>
                </a>

                <button @click="startMonitoring()" class="flex items-center p-4 bg-red-50 dark:bg-gray-800 rounded-lg hover:bg-red-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-8 h-8 text-red-600 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-gray-200">بدء المراقبة</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">تفعيل جميع الكاميرات</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Monitoring Sessions Table -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">جلسات المراقبة النشطة</h3>
                <div class="flex space-x-2">
                    <button @click="refreshData()" class="btn btn-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        تحديث
                    </button>
                    <button @click="exportData()" class="btn btn-outline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تصدير
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الجلسة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المكان</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الكاميرات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المدة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">جلسة المراقبة #001</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">2024-01-15 10:30</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">المبنى الرئيسي</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">الطابق الأول</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">4 كاميرات</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">1080p</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2:45:30</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="viewSession(1)" class="text-blue-600 hover:text-blue-900 ml-2">عرض</button>
                                <button @click="stopSession(1)" class="text-red-600 hover:text-red-900 ml-2">إيقاف</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">جلسة المراقبة #002</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">2024-01-15 09:15</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">مواقف السيارات</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">الخارج</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">6 كاميرات</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">4K</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">نشط</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">3:12:45</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="viewSession(2)" class="text-blue-600 hover:text-blue-900 ml-2">عرض</button>
                                <button @click="stopSession(2)" class="text-red-600 hover:text-red-900 ml-2">إيقاف</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function monitoringSystem() {
    return {
        loading: false,
        sessions: [],
        
        init() {
            this.loadSessions();
        },
        
        async loadSessions() {
            this.loading = true;
            try {
                // Load monitoring sessions
                this.sessions = await fetch('/api/monitoring/sessions').then(res => res.json());
            } catch (error) {
                console.error('Failed to load sessions:', error);
            } finally {
                this.loading = false;
            }
        },
        
        startMonitoring() {
            this.$root.showNotification('بدء المراقبة لجميع الكاميرات', 'success');
        },
        
        refreshData() {
            this.loadSessions();
            this.$root.showNotification('تم تحديث البيانات', 'success');
        },
        
        exportData() {
            this.$root.showNotification('جاري تصدير البيانات...', 'info');
        },
        
        viewSession(id) {
            window.location.href = `/monitoring/sessions/${id}`;
        },
        
        stopSession(id) {
            if (confirm('هل أنت متأكد من إيقاف هذه الجلسة؟')) {
                this.$root.showNotification('تم إيقاف الجلسة', 'success');
            }
        }
    }
}
</script>
@endsection

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach ($columns as $label)
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ $label }}
                                    </th>
                                @endforeach
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($records as $record)
                                <tr>
                                    @foreach (array_keys($columns) as $field)
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ data_get($record, $field) ?? '—' }}
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 space-x-2">
                                        <a href="{{ route($resourceRoute.'.show', $record) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route($resourceRoute.'.edit', $record) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route($resourceRoute.'.destroy', $record) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columns) + 1 }}" class="px-4 py-4 text-center text-sm text-gray-500">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($records, 'hasPages') && $records->hasPages())
                    <div class="mt-4">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
