@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">لوحات التحكم</h2>
        <button onclick="showNotification('فتح نموذج إنشاء لوحة تحكم جديدة', 'info')" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all hover-scale">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                لوحة تحكم جديدة
            </span>
        </button>
    </div>
@endsection

@section('content')
    <div x-data="dashboardsPage()" x-init="init()" x-cloak>
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">البحث</label>
                    <input type="text" 
                           x-model="search" 
                           @input="filterDashboards()"
                           placeholder="ابحث عن لوحة تحكم..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">النوع</label>
                    <select x-model="typeFilter" 
                            @change="filterDashboards()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الأنواع</option>
                        <option value="performance">أداء</option>
                        <option value="analytics">تحليلات</option>
                        <option value="monitoring">مراقبة</option>
                        <option value="security">أمان</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الحالة</label>
                    <select x-model="statusFilter" 
                            @change="filterDashboards()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="draft">مسودة</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Dashboards -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي لوحات التحكم</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_dashboards || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Dashboards -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">لوحات التحكم النشطة</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.active_dashboards || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Widgets -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي الـ Widgets</p>
                        <p class="text-3xl font-bold text-purple-600" x-text="statistics.total_widgets || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                </div>
            </div>

             <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">المشاهدات اليوم</p>
                        <p class="text-3xl font-bold text-orange-600" x-text="statistics.daily_views || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
            <template x-for="dashboard in filteredDashboards" :key="dashboard.id">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                    <div class="relative h-48" :class="{
                        'bg-gradient-to-r from-blue-500 to-purple-600': dashboard.type === 'performance',
                        'bg-gradient-to-r from-green-500 to-teal-600': dashboard.type === 'analytics',
                        'bg-gradient-to-r from-orange-500 to-red-600': dashboard.type === 'monitoring',
                        'bg-gradient-to-r from-purple-500 to-pink-600': dashboard.type === 'security'
                    }">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h3 class="text-xl font-bold" x-text="dashboard.name"></h3>
                                <p class="text-sm mt-2" x-text="dashboard.description"></p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-white bg-opacity-20 text-white"
                                  :class="{
                                      'bg-blue-100 text-blue-800': dashboard.status === 'active',
                                      'bg-gray-100 text-gray-800': dashboard.status === 'inactive',
                                      'bg-yellow-100 text-yellow-800': dashboard.status === 'draft'
                                  }"
                                  x-text="dashboard.status_text"></span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs rounded-full" 
                                      :class="{
                                          'bg-blue-100 text-blue-800': dashboard.type === 'performance',
                                          'bg-green-100 text-green-800': dashboard.type === 'analytics',
                                          'bg-orange-100 text-orange-800': dashboard.type === 'monitoring',
                                          'bg-purple-100 text-purple-800': dashboard.type === 'security'
                                      }"
                                      x-text="dashboard.type_text"></span>
                                <span class="text-xs ml-2" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <span x-text="dashboard.widgets_count || '0'"></span> widgets
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="viewDashboard(dashboard)" 
                                        class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                    عرض
                                </button>
                                <button @click="editDashboard(dashboard)" 
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                    تعديل
                                </button>
                            </div>
                        </div>
                        <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-600'">
                            <div class="flex items-center justify-between mb-2">
                                <span>المشاهدات:</span>
                                <span x-text="dashboard.views_count || '0'"></span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span>آخر تحديث:</span>
                                <span x-text="dashboard.updated_at"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>المنشئ:</span>
                                <span x-text="dashboard.created_by"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Dashboard Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                لوحة التحكم
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                النوع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الـ Widgets
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                المشاهدات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="dashboard in filteredDashboards" :key="dashboard.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                            <span x-text="dashboard.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="dashboard.name"></div>
                                            <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="dashboard.description"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-blue-100 text-blue-800': dashboard.type === 'performance',
                                              'bg-green-100 text-green-800': dashboard.type === 'analytics',
                                              'bg-orange-100 text-orange-800': dashboard.type === 'monitoring',
                                              'bg-purple-100 text-purple-800': dashboard.type === 'security'
                                          }"
                                          x-text="dashboard.type_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <span x-text="dashboard.widgets_count || '0'"></span>
                                        <span class="text-xs ml-1" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">widgets</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <span x-text="dashboard.views_count || '0'"></span>
                                        <span class="text-xs ml-1" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">مشاهدة</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-green-100 text-green-800': dashboard.status === 'active',
                                              'bg-gray-100 text-gray-800': dashboard.status === 'inactive',
                                              'bg-yellow-100 text-yellow-800': dashboard.status === 'draft'
                                          }"
                                          x-text="dashboard.status_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="viewDashboard(dashboard)" 
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            عرض
                                        </button>
                                        <button @click="editDashboard(dashboard)" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            تعديل
                                        </button>
                                        <button @click="duplicateDashboard(dashboard)" 
                                                class="text-green-600 hover:text-green-900 font-medium">
                                            نسخ
                                        </button>
                                        <button @click="deleteDashboard(dashboard)" 
                                                class="text-red-600 hover:text-red-900 font-medium">
                                            حذف
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
                        عرض <span x-text="filteredDashboards.length"></span> من <span x-text="dashboards.length"></span> لوحة تحكم
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
        function dashboardsPage() {
            return {
                darkMode: false,
                dashboards: [],
                filteredDashboards: [],
                statistics: {},
                search: '',
                typeFilter: '',
                statusFilter: '',
                currentPage: 1,
                totalPages: 1,
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadDashboards();
                    this.loadStatistics();
                },
                
                async loadDashboards() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/dashboards');
                        const data = await response.json();
                        this.dashboards = data.dashboards.map(dashboard => ({
                            ...dashboard,
                            status_text: this.getStatusText(dashboard.status),
                            type_text: this.getTypeText(dashboard.type)
                        }));
                        this.filteredDashboards = [...this.dashboards];
                    } catch (error) {
                        console.error('Failed to load dashboards:', error);
                        this.$root.showNotification('فشل تحميل لوحات التحكم', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async loadStatistics() {
                    try {
                        const response = await fetch('/api/dashboards/statistics');
                        this.statistics = await response.json();
                    } catch (error) {
                        console.error('Failed to load statistics:', error);
                    }
                },
                
                filterDashboards() {
                    let filtered = [...this.dashboards];
                    
                    // Search filter
                    if (this.search) {
                        filtered = filtered.filter(dashboard => 
                            dashboard.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            dashboard.description?.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                    
                    // Type filter
                    if (this.typeFilter) {
                        filtered = filtered.filter(dashboard => dashboard.type === this.typeFilter);
                    }
                    
                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(dashboard => dashboard.status === this.statusFilter);
                    }
                    
                    this.filteredDashboards = filtered;
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'inactive': return 'غير نشط';
                        case 'draft': return 'مسودة';
                        default: return status;
                    }
                },
                
                getTypeText(type) {
                    switch(type) {
                        case 'performance': return 'أداء';
                        case 'analytics': return 'تحليلات';
                        case 'monitoring': return 'مراقبة';
                        case 'security': return 'أمان';
                        default: return type;
                    }
                },
                
                viewDashboard(dashboard) {
                    this.$root.showNotification(`عرض لوحة التحكم: ${dashboard.name}`, 'info');
                    window.location.href = `/dashboards/${dashboard.id}`;
                },
                
                editDashboard(dashboard) {
                    this.$root.showNotification(`تعديل لوحة التحكم: ${dashboard.name}`, 'info');
                    window.location.href = `/dashboards/${dashboard.id}/edit`;
                },
                
                async duplicateDashboard(dashboard) {
                    try {
                        const response = await fetch(`/api/dashboards/${dashboard.id}/duplicate`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            this.$root.showNotification(`تم نسخ لوحة التحكم: ${dashboard.name}`, 'success');
                            this.loadDashboards();
                            this.loadStatistics();
                        } else {
                            throw new Error('Failed to duplicate dashboard');
                        }
                    } catch (error) {
                        console.error('Failed to duplicate dashboard:', error);
                        this.$root.showNotification('فشل نسخ لوحة التحكم', 'error');
                    }
                },
                
                async deleteDashboard(dashboard) {
                    if (confirm(`هل أنت متأكد من حذف لوحة التحكم "${dashboard.name}"؟`)) {
                        try {
                            const response = await fetch(`/api/dashboards/${dashboard.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                this.$root.showNotification(`تم حذف لوحة التحكم "${dashboard.name}" بنجاح`, 'success');
                                this.loadDashboards();
                                this.loadStatistics();
                            } else {
                                throw new Error('Failed to delete dashboard');
                            }
                        } catch (error) {
                            console.error('Failed to delete dashboard:', error);
                            this.$root.showNotification('فشل حذف لوحة التحكم', 'error');
                        }
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
