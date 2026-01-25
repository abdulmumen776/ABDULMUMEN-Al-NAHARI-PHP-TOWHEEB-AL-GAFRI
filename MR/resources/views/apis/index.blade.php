@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">الـ APIs</h2>
        <a href="{{ route('apis.create') }}" 
                class="btn btn-primary">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة API جديد
            </span>
        </a>
    </div>
@endsection

@section('content')
    <div x-data="apisPage()" x-init="init()" x-cloak>
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">البحث</label>
                    <input type="text" 
                           x-model="search" 
                           @input="filterApis()"
                           placeholder="ابحث عن API..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">العميل</label>
                    <select x-model="clientFilter" 
                            @change="filterApis()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع العملاء</option>
                        <template x-for="client in clients" :key="client.id">
                            <option :value="client.id" x-text="client.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الحالة</label>
                    <select x-model="statusFilter" 
                            @change="filterApis()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="monitored">مراقب</option>
                        <option value="error">خطأ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">النوع</label>
                    <select x-model="typeFilter" 
                            @change="filterApis()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الأنواع</option>
                        <option value="rest">REST</option>
                        <option value="graphql">GraphQL</option>
                        <option value="soap">SOAP</option>
                        <option value="websocket">WebSocket</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total APIs -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي الـ APIs</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_apis || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active APIs -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">الـ APIs النشطة</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.active_apis || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monitored APIs -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">الـ APIs المراقبة</p>
                        <p class="text-3xl font-bold text-purple-600" x-text="statistics.monitored_apis || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Error Rate -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">معدل الخطأ</p>
                        <p class="text-3xl font-bold text-red-600" x-text="statistics.error_rate || '0%'"></p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Performance Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Response Time Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">وقت الاستجابة</h3>
                <div class="chart-container">
                    <canvas id="responseTimeChart"></canvas>
                </div>
            </div>

            <!-- Success Rate Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">معدل النجاح</h3>
                <div class="chart-container">
                    <canvas id="successRateChart"></canvas>
                </div>
            </div>
        </div>

        <!-- APIs Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                API
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العميل
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                النوع
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الأداء
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="api in filteredApis" :key="api.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                            <span x-text="api.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="api.name"></div>
                                            <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="api.base_url"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="api.client_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-blue-100 text-blue-800': api.type === 'rest',
                                              'bg-green-100 text-green-800': api.type === 'graphql',
                                              'bg-yellow-100 text-yellow-800': api.type === 'soap',
                                              'bg-purple-100 text-purple-800': api.type === 'websocket'
                                          }"
                                          x-text="api.type_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 text-xs rounded-full" 
                                              :class="{
                                                  'bg-green-100 text-green-800': api.status === 'active',
                                                  'bg-gray-100 text-gray-800': api.status === 'inactive',
                                                  'bg-blue-100 text-blue-800': api.status === 'monitored',
                                                  'bg-red-100 text-red-800': api.status === 'error'
                                              }"
                                              x-text="api.status_text"></span>
                                        <span class="ml-2 w-2 h-2 rounded-full" 
                                              :class="{
                                                  'bg-green-500': api.status === 'active',
                                                  'bg-gray-500': api.status === 'inactive',
                                                  'bg-blue-500': api.status === 'monitored',
                                                  'bg-red-500': api.status === 'error'
                                              }"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <div class="flex items-center">
                                            <span x-text="api.avg_response_time || 'N/A'"></span>
                                            <span class="text-xs ml-1" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">ms</span>
                                        </div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'">
                                            النجاح: <span x-text="api.success_rate || '0'"></span>%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="editApi(api)" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            تعديل
                                        </button>
                                        <button @click="deleteApi(api)" 
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
                        عرض <span x-text="filteredApis.length"></span> من <span x-text="apis.length"></span> API
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
        function apisPage() {
            return {
                darkMode: false,
                apis: @json($initialData['apis']),
                filteredApis: [],
                clients: @json($initialData['clients']),
                statistics: {},
                search: '',
                clientFilter: '',
                statusFilter: '',
                typeFilter: '',
                currentPage: 1,
                totalPages: 1,
                loading: false,
                
                init() {
                    this.darkMode = document.documentElement.classList.contains('dark');
                    // Initialize filteredApis with all APIs
                    this.filteredApis = [...this.apis];
                    // Set statistics from controller
                    this.statistics = @json($initialData['statistics']);
                    // Initialize charts
                    this.initCharts();
                },
                
                calculateStatistics() {
                    const total = this.apis.length;
                    const active = this.apis.filter(api => api.status === 'active').length;
                    const monitored = this.apis.filter(api => api.status === 'monitored').length;
                    const errorRate = total > 0 ? Math.round((this.apis.filter(api => api.status === 'error').length / total) * 100) : 0;
                    
                    this.statistics = {
                        total_apis: total,
                        active_apis: active,
                        monitored_apis: monitored,
                        error_rate: errorRate + '%'
                    };
                },
                
                async loadApis() {
                    this.loading = true;
                    try {
                        const response = await fetch('/api/apis');
                        const data = await response.json();
                        this.apis = data.apis.map(api => ({
                            ...api,
                            status_text: this.getStatusText(api.status),
                            type_text: this.getTypeText(api.type)
                        }));
                        this.filteredApis = [...this.apis];
                    } catch (error) {
                        console.error('Failed to load APIs:', error);
                        this.$root.showNotification('فشل تحميل الـ APIs', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async loadClients() {
                    try {
                        const response = await fetch('/api/clients');
                        const data = await response.json();
                        this.clients = data.clients;
                    } catch (error) {
                        console.error('Failed to load clients:', error);
                    }
                },
                
                async loadStatistics() {
                    try {
                        const response = await fetch('/api/apis/statistics');
                        this.statistics = await response.json();
                    } catch (error) {
                        console.error('Failed to load statistics:', error);
                    }
                },
                
                filterApis() {
                    this.filteredApis = this.apis.filter(api => {
                        // Search by name or URL
                        const matchesSearch = !this.search || 
                            (api.name && api.name.toLowerCase().includes(this.search.toLowerCase())) ||
                            (api.base_url && api.base_url.toLowerCase().includes(this.search.toLowerCase()));
                            
                        // Filter by client if clientFilter is set
                        const matchesClient = !this.clientFilter || 
                            (api.client && api.client.id == this.clientFilter);
                            
                        // Filter by status if statusFilter is set
                        const matchesStatus = !this.statusFilter || 
                            api.status === this.statusFilter;
                            
                        // Filter by type if typeFilter is set
                        const matchesType = !this.typeFilter || 
                            api.type === this.typeFilter;
                            
                        return matchesSearch && matchesClient && matchesStatus && matchesType;
                    });
                    
                    // Update pagination
                    this.currentPage = 1;
                    this.totalPages = Math.ceil(this.filteredApis.length / 10);
                    
                    // Update statistics based on filtered results
                    this.calculateStatistics();
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'inactive': return 'غير نشط';
                        case 'monitored': return 'مراقب';
                        case 'error': return 'خطأ';
                        default: return status;
                    }
                },
                
                getTypeText(type) {
                    switch(type) {
                        case 'rest': return 'REST';
                        case 'graphql': return 'GraphQL';
                        case 'soap': return 'SOAP';
                        case 'websocket': return 'WebSocket';
                        default: return type;
                    }
                },
                
                initCharts() {
                    // Get real data for charts
                    const responseTimeData = this.apis.slice(0, 7).map(api => api.avg_response_time || 0);
                    const successRateData = this.apis.slice(0, 5).map(api => api.success_rate || 0);
                    const apiNames = this.apis.slice(0, 5).map(api => api.name || 'API');
                    
                    // Response Time Chart
                    const responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
                    window.charts.createLineChart(responseTimeCtx, {
                        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '24:00'],
                        datasets: [{
                            label: 'وقت الاستجابة (ms)',
                            data: responseTimeData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }]
                    });
                    
                    // Success Rate Chart
                    const successRateCtx = document.getElementById('successRateChart').getContext('2d');
                    window.charts.createBarChart(successRateCtx, {
                        labels: apiNames,
                        datasets: [{
                            label: 'معدل النجاح (%)',
                            data: successRateData,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1
                        }]
                    });
                },
                
                viewApi(api) {
                    // Navigate to the success page for the API
                    window.location.href = `/apis/${api.id}/success`;
                },
                
                async testApi(api) {
                    this.loading = true;
                    try {
                        const response = await fetch(`/apis/${api.id}/test`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            // Show success message
                            this.$root.showNotification(`تم اختبار الـ API بنجاح: ${result.message || ''}`, 'success');
                            
                            // Update the API status in the UI if it changed
                            const apiIndex = this.apis.findIndex(a => a.id === api.id);
                            if (apiIndex !== -1) {
                                this.apis[apiIndex].status = result.api?.status || api.status;
                                this.apis[apiIndex].status_text = this.getStatusText(this.apis[apiIndex].status);
                                // Force UI update
                                this.filterApis();
                            }
                        } else {
                            throw new Error(result.message || 'فشل اختبار الـ API');
                        }
                    } catch (error) {
                        console.error('Error testing API:', error);
                        this.$root.showNotification(`خطأ في اختبار الـ API: ${error.message}`, 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                editApi(api) {
                    window.location.href = `/apis/${api.id}/edit`;
                },
                
                async deleteApi(api) {
                    if (confirm('هل أنت متأكد من حذف هذا الـ API؟')) {
                        try {
                            this.loading = true;
                            const response = await fetch(`/apis/${api.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            });
                            
                            const result = await response.json();
                            
                            if (result.success) {
                                alert('تم حذف الـ API بنجاح');
                                // Remove the API from the list
                                this.apis = this.apis.filter(a => a.id !== api.id);
                                this.filterApis();
                                this.loadApis();
                                this.loadStatistics();
                            } else {
                                throw new Error('Failed to delete API');
                            }
                        } catch (error) {
                            console.error('Failed to delete API:', error);
                            this.$root.showNotification('فشل حذف API', 'error');
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
