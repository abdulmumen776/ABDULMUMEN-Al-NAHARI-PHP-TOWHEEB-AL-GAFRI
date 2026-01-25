@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-bold text-gradient">توكنات الـ API</h2>
@endsection

@section('content')
    <div x-data="tokensPage()" x-init="init()" x-cloak>
        <!-- Header with Create Button -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">توكنات الـ API الخاصة بك</h3>
                <a href="{{ route('tokens.create') }}" 
                class="btn btn-primary">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    توكن جديد
                </span>
            </a>
            </div>
            
            <!-- Client Filter -->
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">تصفية حسب العميل:</label>
                <select x-model="selectedClient" 
                        @change="filterTokensByClient()"
                        class="form-input w-64">
                    <option value="">جميع العملاء</option>
                    <template x-for="client in clients" :key="client.id">
                        <option :value="client.id" x-text="client.name"></option>
                    </template>
                </select>
                <span class="text-sm text-gray-500" :class="darkMode ? 'text-gray-400' : ''">
                    عرض <span x-text="filteredTokens.length"></span> من <span x-text="tokens.length"></span> توكن
                </span>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Tokens -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي التوكنات</p>
                        <p class="text-3xl font-bold text-blue-600" x-text="statistics.total_tokens || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012-2h4m0 0V5a2 2 0 012-2h-4m0 0a2 2 0 00-2-2V5a2 2 0 00-2-2h-4m-6 9a2 2 0 002-2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m0 0a2 2 0 00-2-2v-2a2 2 0 00-2-2h-2m-6 9a2 2 0 002-2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m0 0a2 2 0 00-2-2v-2a2 2 0 00-2-2h-2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Tokens -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التوكنات النشطة</p>
                        <p class="text-3xl font-bold text-green-600" x-text="statistics.active_tokens || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expired Tokens -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التوكنات المنتهية</p>
                        <p class="text-3xl font-bold text-red-600" x-text="statistics.expired_tokens || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inactive Tokens -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover" :class="darkMode ? 'bg-gray-800' : ''">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">غير نشطة</p>
                        <p class="text-3xl font-bold text-gray-600" x-text="statistics.inactive_tokens || '0'"></p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 0A9 9 0 0018.364 18.364M9 10h.01M15 10h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tokens Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                التوكن
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العميل
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الصلاحيات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحالة
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                آخر استخدام
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                انتهاء الصلاحية
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="token in filteredTokens" :key="token.id">
                            <tr class="hover:bg-white-50 dark:hover:bg-gray-600 transition-all cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold ml-3">
                                            <span x-text="token.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="token.name"></div>
                                            <div class="text-sm font-mono" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="token.formatted_token"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="token.client_name || 'غير محدد'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="ability in (token.abilities || [])" :key="ability">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800" x-text="ability"></span>
                                        </template>
                                        <span x-show="!token.abilities || token.abilities.length === 0" class="text-gray-500">لا توجد صلاحيات</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-green-100 text-green-800': token.status === 'active',
                                              'bg-gray-100 text-gray-800': token.status === 'inactive',
                                              'bg-red-100 text-red-800': token.status === 'expired'
                                          }"
                                          x-text="getStatusText(token.status)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <div x-text="token.last_used_at || 'لم يُستخدم بعد'"></div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'" x-text="token.days_since_last_use ? token.days_since_last_use + ' يوم' : ''"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'">
                                        <div x-text="token.expires_at"></div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-500' : 'text-gray-500'" x-text="token.remaining_days ? token.remaining_days + ' يوم متبقي' : 'لا ينتهي'"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <!-- Test Button -->
                                        <button type="button" @click="console.log('Test clicked!')" 
                                                class="text-purple-600 hover:text-purple-900 font-medium">
                                            اختبار
                                        </button>
                                        <button type="button" @click="viewToken(token)" 
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                            عرض
                                        </button>
                                        <button type="button" @click="editToken(token)" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            تعديل
                                        </button>
                                        <button type="button" @click="revokeToken(token)" 
                                                x-show="token.status === 'active'"
                                                class="text-red-600 hover:text-red-900 font-medium">
                                            إلغاء
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div x-show="tokens.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012-2h4m0 0V5a2 2 0 012-2h-4m0 0a2 2 0 00-2-2V5a2 2 0 00-2-2h-4m-6 9a2 2 0 002-2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m0 0a2 2 0 00-2-2v-2a2 2 0 00-2-2h-2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900" :class="darkMode ? 'text-gray-200' : ''">لا توجد توكنات</h3>
                <p class="mt-1 text-sm text-gray-500" :class="darkMode ? 'text-gray-400' : ''">ابدأ بإنشاء توكن API جديد للوصول إلى النظام</p>
                <div class="mt-6">
                    <button onclick="showNotification('فتح نموذج إنشاء توكن جديد', 'info')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all">
                        إنشاء توكن جديد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tokensPage() {
            return {
                darkMode: false,
                tokens: [],
                filteredTokens: [],
                clients: [],
                selectedClient: '',
                statistics: {},
                loading: false,
                
                init() {
                    this.darkMode = document.body.classList.contains('dark-mode');
                    this.loadTokens();
                    this.loadClients();
                    this.loadStatistics();
                },
                
                async loadTokens() {
                    this.loading = true;
                    try {
                        // Use tokens data passed from controller instead of API call
                        console.log('Loading tokens data...');
                        this.tokens = @json($tokens);
                        console.log('Tokens loaded:', this.tokens);
                        this.filteredTokens = [...this.tokens];
                        console.log('Filtered tokens:', this.filteredTokens);
                    } catch (error) {
                        console.error('Failed to load tokens:', error);
                        this.$root.showNotification('فشل تحميل التوكنات', 'error');
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
                
                filterTokensByClient() {
                    if (this.selectedClient === '') {
                        this.filteredTokens = [...this.tokens];
                    } else {
                        this.filteredTokens = this.tokens.filter(token => 
                            token.client_id == this.selectedClient
                        );
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
                
                getStatusText(status) {
                    switch(status) {
                        case 'active': return 'نشط';
                        case 'inactive': return 'غير نشط';
                        case 'expired': return 'منتهي';
                        default: return status;
                    }
                },
                
                formatToken(token) {
                    if (!token) return '';
                    return token.substring(0, 8) + '...' + token.substring(token.length - 8);
                },
                
                viewToken(token) {
                    console.log('View token clicked:', token.id);
                    window.location.href = `/tokens/${token.id}`;
                },
                
                editToken(token) {
                    console.log('Edit token clicked:', token.id);
                    window.location.href = `/tokens/${token.id}/edit`;
                },
                
                async revokeToken(token) {
                    console.log('Revoking token:', token);
                    if (confirm(`هل أنت متأكد من إلغاء التوكن "${token.name}"؟`)) {
                        try {
                            const response = await fetch(`/api/tokens/${token.id}/revoke`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            
                            if (response.ok) {
                                console.log('Token revoked successfully');
                                alert('تم إلغاء التوكن بنجاح');
                                this.loadTokens();
                                this.loadStatistics();
                            } else {
                                throw new Error('Failed to revoke token');
                            }
                        } catch (error) {
                            console.error('Failed to revoke token:', error);
                            alert('فشل إلغاء التوكن');
                        }
                    }
                }
            }
        }
    </script>
@endsection
