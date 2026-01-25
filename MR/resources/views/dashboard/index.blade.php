@extends('layouts.app')

@section('header')
    <h2 class="text-3xl font-bold text-gradient mb-2">لوحة التحكم الرئيسية</h2>
    <p class="text-gray-600 dark:text-gray-400">مرحباً بك في لوحة التحكم الشاملة لنظام المراقبة المتقدم</p>
@endsection

@section('content')
    <div x-data="dashboard()" x-init="init()" x-cloak>
        <!-- Welcome Section -->
        <div class="card p-8 mb-8 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">مرحباً بك في لوحة التحكم</h1>
                    <p class="text-blue-100">نظام المراقبة المتقدم - إدارة شاملة ومتطورة</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Clients -->
            <div class="card card-success p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="text-3xl font-bold text-gradient-success" x-text="statistics.total_clients || '0'"></span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">إجمالي العملاء</h3>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-green-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>12% من الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <!-- Active Operations -->
            <div class="card card-warning p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="text-3xl font-bold text-gradient-warning" x-text="statistics.active_operations || '0'"></span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">العمليات النشطة</h3>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-green-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>8% من الأسبوع الماضي</span>
                    </div>
                </div>
            </div>

            <!-- Monitored APIs -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="text-3xl font-bold text-gradient" x-text="statistics.monitored_apis || '0'"></span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">الـ APIs المراقبة</h3>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-red-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span>3% من الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <!-- Open Alerts -->
            <div class="card card-danger p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="text-3xl font-bold text-gradient-danger" x-text="statistics.open_alerts || '0'"></span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">التنبيهات المفتوحة</h3>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-green-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>5% من الأمس</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Performance Chart -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">أداء النظام</h3>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Operations Distribution -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">توزيع العمليات</h3>
                <div class="chart-container">
                    <canvas id="operationsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Operations -->
            <div class="lg:col-span-2 card p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">العمليات الأخيرة</h3>
                <div class="space-y-3">
                    <template x-for="operation in recentOperations" :key="operation.id">
                        <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center ml-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200" x-text="operation.name"></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="operation.client_name"></p>
                                </div>
                            </div>
                            <div class="text-left">
                                <span class="badge" 
                                      :class="{
                                          'badge-success': operation.status === 'active',
                                          'badge-warning': operation.status === 'scheduled',
                                          'badge-primary': operation.status === 'completed',
                                          'badge-danger': operation.status === 'cancelled'
                                      }"
                                      x-text="operation.status_text"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- System Status -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">حالة النظام</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">قاعدة البيانات</span>
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Redis Cache</span>
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Queue Worker</span>
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">API Monitoring</span>
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Email Service</span>
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 card p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إجراءات سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <button @click="showNotification('بدء مراقبة الـ APIs', 'info')" 
                        class="btn btn-primary">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    بدء المراقبة
                </button>
                <button @click="showNotification('تجميع بيانات الأداء', 'info')" 
                        class="btn btn-success">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    تجميع البيانات
                </button>
                <button @click="showNotification('تحديث التخزين المؤقت', 'info')" 
                        class="btn btn-outline">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    تحديث التخزين
                </button>
                <button @click="generateReport()" 
                        class="btn btn-outline">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    إنشاء تقرير
                </button>
            </div>
        </div>
    </div>

    <script>
        function dashboard() {
            return {
                darkMode: false,
                statistics: {},
                recentOperations: [],
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadStatistics();
                    this.loadRecentOperations();
                    this.initCharts();
                },
                
                async loadStatistics() {
                    try {
                        const response = await fetch('/api/dashboard/statistics');
                        this.statistics = await response.json();
                    } catch (error) {
                        console.error('Failed to load statistics:', error);
                    }
                },
                
                async loadRecentOperations() {
                    try {
                        const response = await fetch('/api/operations/recent');
                        this.recentOperations = await response.json();
                    } catch (error) {
                        console.error('Failed to load recent operations:', error);
                    }
                },
                
                initCharts() {
                    // Performance Chart
                    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
                    new Chart(performanceCtx, {
                        type: 'line',
                        data: {
                            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
                            datasets: [{
                                label: 'استجابة الـ API (ms)',
                                data: [120, 135, 125, 140, 130, 145, 138],
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            }, {
                                label: 'استخدام الذاكرة (%)',
                                data: [65, 70, 68, 72, 75, 71, 69],
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                    
                    // Operations Chart
                    const operationsCtx = document.getElementById('operationsChart').getContext('2d');
                    new Chart(operationsCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['نشط', 'مكتمل', 'مجدول', 'ملغي'],
                            datasets: [{
                                data: [45, 30, 20, 5],
                                backgroundColor: [
                                    'rgb(16, 185, 129)',
                                    'rgb(59, 130, 246)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                },
                
                showNotification(message, type = 'info') {
                    const notification = { id: Date.now(), message, type, show: true };
                    this.$root.notifications.push(notification);
                    setTimeout(() => {
                        notification.show = false;
                        setTimeout(() => {
                            this.$root.notifications = this.$root.notifications.filter(n => n.id !== notification.id);
                        }, 300);
                    }, 5000);
                },

                generateReport() {
                    window.location.href = "{{ route('reports.generate') }}";
                }
            }
        }
    </script>
@endsection
