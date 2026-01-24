@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">التنبيهات</h2>
        <div class="flex space-x-2">
            <button onclick="showNotification('تحديث جميع التنبيهات', 'info')" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all hover-scale">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    تحديث
                </span>
            </button>
            <a href="{{ route('alerts.create') }}" 
                class="btn btn-primary">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    تنبيه جديد
                </span>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div x-data="alertsPage()" x-init="init()" x-cloak>
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">البحث</label>
                    <input type="text" 
                           x-model="search" 
                           @input="filterAlerts()"
                           placeholder="ابحث عن تنبيه..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الخطورة</label>
                    <select x-model="severityFilter" 
                            @change="filterAlerts()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الخطورات</option>
                        <option value="low">منخفض</option>
                        <option value="medium">متوسط</option>
                        <option value="high">عالي</option>
                        <option value="critical">حرج</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الحالة</label>
                    <select x-model="statusFilter" 
                            @change="filterAlerts()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الحالات</option>
                        <option value="open">مفتوح</option>
                        <option value="acknowledged">معترف به</option>
                        <option value="resolved">تم الحل</option>
                        <option value="dismissed">متجاهل</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">النوع</label>
                    <select x-model="typeFilter" 
                            @change="filterAlerts()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الأنواع</option>
                        <option value="performance">أداء</option>
                        <option value="security">أمان</option>
                        <option value="system">نظام</option>
                        <option value="api">API</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Alerts -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي التنبيهات</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_alerts || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Open Alerts -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التنبيهات المفتوحة</p>
                        <p class="text-3xl font-bold text-red-600" x-text="statistics.open_alerts || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Critical Alerts -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التنبيهات الحرجة</p>
                        <p class="text-3xl font-bold text-orange-600" x-text="statistics.critical_alerts || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6-3-6h-.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Resolved Today -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">تم حلها اليوم</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.resolved_today || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Timeline -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">التنبيهات الأخيرة</h3>
            <div class="space-y-4">
                <template x-for="alert in recentAlerts" :key="alert.id">
                    <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3"
                                 :class="{
                                     'bg-red-100': alert.severity === 'critical',
                                     'bg-orange-100': alert.severity === 'high',
                                     'bg-yellow-100': alert.severity === 'medium',
                                     'bg-blue-100': alert.severity === 'low'
                                 }">
                                <svg class="w-5 h-5" 
                                     :class="{
                                         'text-red-600': alert.severity === 'critical',
                                         'text-orange-600': alert.severity === 'high',
                                         'text-yellow-600': alert.severity === 'medium',
                                         'text-blue-600': alert.severity === 'low'
                                     }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-800'" x-text="alert.title"></p>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'" x-text="alert.message"></p>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="px-2 py-1 text-xs rounded-full" 
                                  :class="{
                                      'bg-red-100 text-red-800': alert.severity === 'critical',
                                      'bg-orange-100 text-orange-800': alert.severity === 'high',
                                      'bg-yellow-100 text-yellow-800': alert.severity === 'medium',
                                      'bg-blue-100 text-blue-800': alert.severity === 'low'
                                  }"
                                  x-text="alert.severity_text"></span>
                            <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="alert.created_at"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Alerts Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                التنبيه
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الخطورة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                النوع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                التاريخ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="alert in filteredAlerts" :key="alert.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3"
                                             :class="{
                                                 'bg-red-100': alert.severity === 'critical',
                                                 'bg-orange-100': alert.severity === 'high',
                                                 'bg-yellow-100': alert.severity === 'medium',
                                                 'bg-blue-100': alert.severity === 'low'
                                             }">
                                            <svg class="w-5 h-5" 
                                                 :class="{
                                                     'text-red-600': alert.severity === 'critical',
                                                     'text-orange-600': alert.severity === 'high',
                                                     'text-yellow-600': alert.severity === 'medium',
                                                     'text-blue-600': alert.severity === 'low'
                                                 }"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="alert.title"></div>
                                            <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="alert.message"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-red-100 text-red-800': alert.severity === 'critical',
                                              'bg-orange-100 text-orange-800': alert.severity === 'high',
                                              'bg-yellow-100 text-yellow-800': alert.severity === 'medium',
                                              'bg-blue-100 text-blue-800': alert.severity === 'low'
                                          }"
                                          x-text="alert.severity_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-red-100 text-red-800': alert.status === 'open',
                                              'bg-blue-100 text-blue-800': alert.status === 'acknowledged',
                                              'bg-green-100 text-green-800': alert.status === 'resolved',
                                              'bg-gray-100 text-gray-800': alert.status === 'dismissed'
                                          }"
                                          x-text="alert.status_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-purple-100 text-purple-800': alert.type === 'performance',
                                              'bg-red-100 text-red-800': alert.type === 'security',
                                              'bg-blue-100 text-blue-800': alert.type === 'system',
                                              'bg-green-100 text-green-800': alert.type === 'api'
                                          }"
                                          x-text="alert.type_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <div x-text="alert.created_at"></div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'" x-text="alert.resolved_at || 'غير محل'"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="viewAlert(alert)" 
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            عرض
                                        </button>
                                        <button @click="acknowledgeAlert(alert)" 
                                                x-show="alert.status === 'open'"
                                                class="text-green-600 hover:text-green-900 font-medium">
                                            اعتراف
                                        </button>
                                        <button @click="resolveAlert(alert)" 
                                                x-show="alert.status !== 'resolved'"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            حل
                                        </button>
                                        <button @click="dismissAlert(alert)" 
                                                x-show="alert.status !== 'dismissed'"
                                                class="text-gray-600 hover:text-gray-900 font-medium">
                                            تجاهل
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center justify-between">
                    <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-700'">
                        عرض <span x-text="filteredAlerts.length"></span> من <span x-text="alerts.length"></span> تنبيه
                    </div>
                    <div class="flex space-x-2">
                        <button @click="previousPage()" 
                                :disabled="currentPage === 1"
                                class="px-3 py-1 border rounded-md hover:bg-gray-50 disabled:opacity-50"
                                :class="darkMode ? 'border-gray-600 text-gray-300' : 'border-gray-300'">
                            السابق
                        </button>
                        <button @click="nextPage()" 
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1 border rounded-md hover:bg-gray-50 disabled:opacity-50"
                                :class="darkMode ? 'border-gray-600 text-gray-300' : 'border-gray-300'">
                            التالي
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function alertsPage() {
            return {
                darkMode: false,
                alerts: [],
                filteredAlerts: [],
                recentAlerts: [],
                statistics: {},
                search: '',
                severityFilter: '',
                statusFilter: '',
                typeFilter: '',
                currentPage: 1,
                totalPages: 1,
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadAlerts();
                    this.loadStatistics();
                },
                
                async loadAlerts() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/alerts');
                        const data = await response.json();
                        this.alerts = data.alerts.map(alert => ({
                            ...alert,
                            severity_text: this.getSeverityText(alert.severity),
                            status_text: this.getStatusText(alert.status),
                            type_text: this.getTypeText(alert.type)
                        }));
                        this.filteredAlerts = [...this.alerts];
                        this.recentAlerts = this.alerts.slice(0, 5);
                    } catch (error) {
                        console.error('Failed to load alerts:', error);
                        this.$root.showNotification('فشل تحميل التنبيهات', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async loadStatistics() {
                    try {
                        const response = await fetch('/api/alerts/statistics');
                        this.statistics = await response.json();
                    } catch (error) {
                        console.error('Failed to load statistics:', error);
                    }
                },
                
                filterAlerts() {
                    let filtered = [...this.alerts];
                    
                    // Search filter
                    if (this.search) {
                        filtered = filtered.filter(alert => 
                            alert.title.toLowerCase().includes(this.search.toLowerCase()) ||
                            alert.message.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                    
                    // Severity filter
                    if (this.severityFilter) {
                        filtered = filtered.filter(alert => alert.severity === this.severityFilter);
                    }
                    
                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(alert => alert.status === this.statusFilter);
                    }
                    
                    // Type filter
                    if (this.typeFilter) {
                        filtered = filtered.filter(alert => alert.type === this.typeFilter);
                    }
                    
                    this.filteredAlerts = filtered;
                },
                
                getSeverityText(severity) {
                    switch(severity) {
                        case 'low': return 'منخفض';
                        case 'medium': return 'متوسط';
                        case 'high': return 'عالي';
                        case 'critical': return 'حرج';
                        default: return severity;
                    }
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'open': return 'مفتوح';
                        case 'acknowledged': return 'معترف به';
                        case 'resolved': return 'تم الحل';
                        case 'dismissed': return 'متجاهل';
                        default: return status;
                    }
                },
                
                getTypeText(type) {
                    switch(type) {
                        case 'performance': return 'أداء';
                        case 'security': return 'أمان';
                        case 'system': return 'نظام';
                        case 'api': return 'API';
                        default: return type;
                    }
                },
                
                viewAlert(alert) {
                    this.$root.showNotification(`عرض التنبيه: ${alert.title}`, 'info');
                    window.location.href = `/alerts/${alert.id}`;
                },
                
                async acknowledgeAlert(alert) {
                    try {
                        const response = await fetch(`/api/alerts/${alert.id}/acknowledge`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            this.$root.showNotification(`تم الاعتراف بالتنبيه: ${alert.title}`, 'success');
                            this.loadAlerts();
                            this.loadStatistics();
                        } else {
                            throw new Error('Failed to acknowledge alert');
                        }
                    } catch (error) {
                        console.error('Failed to acknowledge alert:', error);
                        this.$root.showNotification('فشل الاعتراف بالتنبيه', 'error');
                    }
                },
                
                async resolveAlert(alert) {
                    try {
                        const response = await fetch(`/api/alerts/${alert.id}/resolve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            this.$root.showNotification(`تم حل التنبيه: ${alert.title}`, 'success');
                            this.loadAlerts();
                            this.loadStatistics();
                        } else {
                            throw new Error('Failed to resolve alert');
                        }
                    } catch (error) {
                        console.error('Failed to resolve alert:', error);
                        this.$root.showNotification('فشل حل التنبيه', 'error');
                    }
                },
                
                async dismissAlert(alert) {
                    try {
                        const response = await fetch(`/api/alerts/${alert.id}/dismiss`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            this.$root.showNotification(`تم تجاهل التنبيه: ${alert.title}`, 'success');
                            this.loadAlerts();
                            this.loadStatistics();
                        } else {
                            throw new Error('Failed to dismiss alert');
                        }
                    } catch (error) {
                        console.error('Failed to dismiss alert:', error);
                        this.$root.showNotification('فشل تجاهل التنبيه', 'error');
                    }
                },
                
                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                }
            }
        }
    </script>
@endsection
