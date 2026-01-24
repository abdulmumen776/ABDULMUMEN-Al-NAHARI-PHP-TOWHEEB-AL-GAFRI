@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">العمليات</h2>
        <a href="{{ route('operations.create') }}" 
                class="btn btn-primary">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة عملية جديدة
            </span>
        </a>
    </div>
@endsection

@section('content')
    <div x-data="operationsPage()" x-init="init()" x-cloak>
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">البحث</label>
                    <input type="text" 
                           x-model="search" 
                           @input="filterOperations()"
                           placeholder="ابحث عن عملية..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">العميل</label>
                    <select x-model="clientFilter" 
                            @change="filterOperations()"
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
                            @change="filterOperations()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="scheduled">مجدول</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">النوع</label>
                    <select x-model="typeFilter" 
                            @change="filterOperations()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الأنواع</option>
                        <option value="monitoring">مراقبة</option>
                        <option value="analysis">تحليل</option>
                        <option value="maintenance">صيانة</option>
                        <option value="backup">نسخ احتياطي</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Operations -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي العمليات</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_operations || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Operations -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">العمليات النشطة</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.active_operations || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Operations -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">العمليات المكتملة</p>
                        <p class="text-3xl font-bold text-purple-600" x-text="statistics.completed_operations || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">معدل النجاح</p>
                        <p class="text-3xl font-bold text-orange-600" x-text="statistics.success_rate || '0%'"></p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Timeline -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">جدول العمليات</h3>
            <div class="space-y-4">
                <template x-for="operation in recentOperations" :key="operation.id">
                    <div class="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3"
                                 :class="{
                                     'bg-blue-100': operation.type === 'monitoring',
                                     'bg-green-100': operation.type === 'analysis',
                                     'bg-yellow-100': operation.type === 'maintenance',
                                     'bg-purple-100': operation.type === 'backup'
                                 }">
                                <svg class="w-5 h-5" 
                                     :class="{
                                         'text-blue-600': operation.type === 'monitoring',
                                         'text-green-600': operation.type === 'analysis',
                                         'text-yellow-600': operation.type === 'maintenance',
                                         'text-purple-600': operation.type === 'backup'
                                     }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-800'" x-text="operation.name"></p>
                                <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'" x-text="operation.client_name"></p>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="px-2 py-1 text-xs rounded-full" 
                                  :class="{
                                      'bg-green-100 text-green-800': operation.status === 'active',
                                      'bg-blue-100 text-blue-800': operation.status === 'scheduled',
                                      'bg-purple-100 text-purple-800': operation.status === 'completed',
                                      'bg-gray-100 text-gray-800': operation.status === 'cancelled'
                                  }"
                                  x-text="operation.status_text"></span>
                            <p class="text-xs mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="operation.scheduled_at"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Operations Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العملية
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
                                التاريخ
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="operation in filteredOperations" :key="operation.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center ml-3"
                                             :class="{
                                                 'bg-blue-100': operation.type === 'monitoring',
                                                 'bg-green-100': operation.type === 'analysis',
                                                 'bg-yellow-100': operation.type === 'maintenance',
                                                 'bg-purple-100': operation.type === 'backup'
                                             }">
                                            <svg class="w-5 h-5" 
                                                 :class="{
                                                     'text-blue-600': operation.type === 'monitoring',
                                                     'text-green-600': operation.type === 'analysis',
                                                     'text-yellow-600': operation.type === 'maintenance',
                                                     'text-purple-600': operation.type === 'backup'
                                                 }"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="operation.name"></div>
                                            <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="operation.description"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="operation.client_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-blue-100 text-blue-800': operation.type === 'monitoring',
                                              'bg-green-100 text-green-800': operation.type === 'analysis',
                                              'bg-yellow-100 text-yellow-800': operation.type === 'maintenance',
                                              'bg-purple-100 text-purple-800': operation.type === 'backup'
                                          }"
                                          x-text="operation.type_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-green-100 text-green-800': operation.status === 'active',
                                              'bg-blue-100 text-blue-800': operation.status === 'scheduled',
                                              'bg-purple-100 text-purple-800': operation.status === 'completed',
                                              'bg-gray-100 text-gray-800': operation.status === 'cancelled'
                                          }"
                                          x-text="operation.status_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <div x-text="operation.scheduled_at"></div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'" x-text="operation.duration || 'غير محدد'"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="viewOperation(operation)" 
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            عرض
                                        </button>
                                        <button @click="editOperation(operation)" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            تعديل
                                        </button>
                                        <button @click="deleteOperation(operation)" 
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
                        عرض <span x-text="filteredOperations.length"></span> من <span x-text="operations.length"></span> عملية
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
        function operationsPage() {
            return {
                darkMode: false,
                operations: [],
                filteredOperations: [],
                clients: [],
                recentOperations: [],
                statistics: {},
                search: '',
                clientFilter: '',
                statusFilter: '',
                typeFilter: '',
                currentPage: 1,
                totalPages: 1,
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadOperations();
                    this.loadClients();
                    this.loadStatistics();
                },
                
                async loadOperations() {
                    this.loading = true;
                    try {
                        // Use operations data passed from controller instead of API call
                        this.operations = @json($operations).map(operation => ({
                            ...operation,
                            status_text: this.getStatusText(operation.status),
                            type_text: this.getTypeText(operation.type)
                        }));
                        this.filteredOperations = [...this.operations];
                        this.recentOperations = this.operations.slice(0, 5);
                    } catch (error) {
                        console.error('Failed to load operations:', error);
                        this.$root.showNotification('فشل تحميل العمليات', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async loadClients() {
                    try {
                        // Use clients data passed from controller instead of API call
                        this.clients = @json($clients);
                    } catch (error) {
                        console.error('Failed to load clients:', error);
                        this.$root.showNotification('فشل تحميل العملاء', 'error');
                    }
                },
                
                async loadStatistics() {
                    try {
                        // Use statistics data passed from controller instead of API call
                        this.statistics = @json($statistics);
                    } catch (error) {
                        console.error('Failed to load statistics:', error);
                        this.$root.showNotification('فشل تحميل الإحصائيات', 'error');
                    }
                },
                
                filterOperations() {
                    let filtered = [...this.operations];
                    
                    // Search filter
                    if (this.search) {
                        filtered = filtered.filter(operation => 
                            operation.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            operation.description?.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                    
                    // Client filter
                    if (this.clientFilter) {
                        filtered = filtered.filter(operation => operation.client_id == this.clientFilter);
                    }
                    
                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(operation => operation.status === this.statusFilter);
                    }
                    
                    // Type filter
                    if (this.typeFilter) {
                        filtered = filtered.filter(operation => operation.type === this.typeFilter);
                    }
                    
                    this.filteredOperations = filtered;
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'scheduled': return 'مجدول';
                        case 'completed': return 'مكتمل';
                        case 'cancelled': return 'ملغي';
                        default: return status;
                    }
                },
                
                getTypeText(type) {
                    switch(type) {
                        case 'monitoring': return 'مراقبة';
                        case 'analysis': return 'تحليل';
                        case 'maintenance': return 'صيانة';
                        case 'backup': return 'نسخ احتياطي';
                        default: return type;
                    }
                },
                
                viewOperation(operation) {
                    this.$root.showNotification(`عرض العملية: ${operation.name}`, 'info');
                    window.location.href = `/operations/${operation.id}`;
                },
                
                editOperation(operation) {
                    this.$root.showNotification(`تعديل العملية: ${operation.name}`, 'info');
                    window.location.href = `/operations/${operation.id}/edit`;
                },
                
                async deleteOperation(operation) {
                    if (confirm(`هل أنت متأكد من حذف العملية "${operation.name}"؟`)) {
                        try {
                            const response = await fetch(`/api/operations/${operation.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                this.$root.showNotification(`تم حذف العملية "${operation.name}" بنجاح`, 'success');
                                this.loadOperations();
                                this.loadStatistics();
                            } else {
                                throw new Error('Failed to delete operation');
                            }
                        } catch (error) {
                            console.error('Failed to delete operation:', error);
                            this.$root.showNotification('فشل حذف العملية', 'error');
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
