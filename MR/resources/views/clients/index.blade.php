@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">العملاء</h2>
        <a href="{{ route('clients.create') }}" 
                class="btn btn-primary">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة عميل جديد
            </span>
        </a>
    </div>
@endsection

@section('content')
    <div x-data="clientsPage()" x-init="init()" x-cloak>
        <!-- Search and Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">البحث</label>
                    <input type="text" 
                           x-model="search" 
                           @input="filterClients()"
                           placeholder="ابحث عن عميل..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الحالة</label>
                    <select x-model="statusFilter" 
                            @change="filterClients()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                        <option value="suspended">محظور</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">الترتيب</label>
                    <select x-model="sortBy" 
                            @change="sortClients()"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'border-gray-300'">
                        <option value="name">الاسم</option>
                        <option value="created_at">تاريخ الإنشاء</option>
                        <option value="status">الحالة</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Clients -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي العملاء</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_clients || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Clients -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">العملاء النشطون</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.active_clients || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Operations -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي العمليات</p>
                        <p class="text-3xl font-bold text-purple-600" x-text="statistics.total_operations || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العميل
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الصناعة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العمليات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الـ APIs
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="client in filteredClients" :key="client.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                            <span x-text="client.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="client.name"></div>
                                            <div class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="client.contact_email"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="client.industry || 'غير محدد'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-green-100 text-green-800': client.status === 'active',
                                              'bg-gray-100 text-gray-800': client.status === 'inactive',
                                              'bg-red-100 text-red-800': client.status === 'suspended'
                                          }"
                                          x-text="client.status_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <span x-text="client.operations_count || '0'"></span>
                                        <span class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'"> عمليات</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <span x-text="client.apis_count || '0'"></span>
                                        <span class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'"> APIs</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <button @click="viewClient(client)" 
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            عرض
                                        </button>
                                        <button @click="editClient(client)" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            تعديل
                                        </button>
                                        <button @click="deleteClient(client)" 
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
                        عرض <span x-text="filteredClients.length"></span> من <span x-text="clients.length"></span> عميل
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
        function clientsPage() {
            return {
                darkMode: false,
                clients: [],
                filteredClients: [],
                statistics: {},
                search: '',
                statusFilter: '',
                sortBy: 'name',
                currentPage: 1,
                totalPages: 1,
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadClients();
                    this.loadStatistics();
                },
                
                async loadClients() {
                    this.loading = true;
                    try {
                        // Use clients data passed from controller instead of API call
                        this.clients = @json($clients).map(client => ({
                            ...client,
                            status_text: this.getStatusText(client.status)
                        }));
                        this.filteredClients = [...this.clients];
                    } catch (error) {
                        console.error('Failed to load clients:', error);
                        this.$root.showNotification('فشل تحميل العملاء', 'error');
                    } finally {
                        this.loading = false;
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
                
                filterClients() {
                    let filtered = [...this.clients];
                    
                    // Search filter
                    if (this.search) {
                        filtered = filtered.filter(client => 
                            client.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            client.contact_email?.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                    
                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(client => client.status === this.statusFilter);
                    }
                    
                    this.filteredClients = filtered;
                },
                
                sortClients() {
                    this.filteredClients.sort((a, b) => {
                        switch(this.sortBy) {
                            case 'name':
                                return a.name.localeCompare(b.name);
                            case 'created_at':
                                return new Date(b.created_at) - new Date(a.created_at);
                            case 'status':
                                return a.status.localeCompare(b.status);
                            default:
                                return 0;
                        }
                    });
                },
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'inactive': return 'غير نشط';
                        case 'suspended': return 'محظور';
                        default: return status;
                    }
                },
                
                viewClient(client) {
                    this.$root.showNotification(`عرض العميل: ${client.name}`, 'info');
                    // Navigate to client details
                    window.location.href = `/clients/${client.id}`;
                },
                
                editClient(client) {
                    this.$root.showNotification(`تعديل العميل: ${client.name}`, 'info');
                    // Navigate to edit form
                    window.location.href = `/clients/${client.id}/edit`;
                },
                
                async deleteClient(client) {
                    if (confirm(`هل أنت متأكد من حذف العميل "${client.name}"؟`)) {
                        try {
                            const response = await fetch(`/api/clients/${client.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                this.$root.showNotification(`تم حذف العميل "${client.name}" بنجاح`, 'success');
                                this.loadClients();
                                this.loadStatistics();
                            } else {
                                throw new Error('Failed to delete client');
                            }
                        } catch (error) {
                            console.error('Failed to delete client:', error);
                            this.$root.showNotification('فشل حذف العميل', 'error');
                        }
                    }
                },
                
                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        // Load page data
                    }
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        // Load page data
                    }
                }
            }
        }
    </script>
@endsection
