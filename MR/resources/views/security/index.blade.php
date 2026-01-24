@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gradient">الأمان والصلاحيات</h2>
        <div class="flex space-x-2">
            <a href="{{ route('security.create') }}" 
                class="btn btn-primary">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إعداد أمان جديد
                </span>
            </a>
            <button onclick="showNotification('بدء فحص أمان شامل', 'info')" 
                    class="btn btn-outline">
                <span class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    فحص أمان
                </span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div x-data="securityPage()" x-init="init()" x-cloak>
        <!-- Security Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Security Score -->
            <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">نقاط الأمان</h3>
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <svg class="w-32 h-32">
                            <circle cx="64" cy="64" r="56" fill="none" 
                                    :class="darkMode ? 'stroke-gray-600' : 'stroke-gray-300'"
                                    stroke-width="8"/>
                            <circle cx="64" cy="64" r="56" fill="none" 
                                    :class="securityScore > 80 ? 'stroke-green-500' : securityScore > 60 ? 'stroke-yellow-500' : 'stroke-red-500'"
                                    stroke-width="8"
                                    :stroke-dasharray="351.86"
                                    :stroke-dashoffset="351.86"
                                    transform="rotate(-90 64 64)"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-3xl font-bold" :class="securityScore > 80 ? 'text-green-600' : securityScore > 60 ? 'text-yellow-600' : 'text-red-600'" x-text="securityScore"></span>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                        <span x-text="securityScore > 80 ? 'أمان ممتاز' : securityScore > 60 ? 'أمان متوسط' : 'أمان منخفض'"></span>
                    </p>
                </div>
            </div>

            <!-- Security Status -->
            <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
                <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">حالة الأمان</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" :class="systemStatus.password_policy === 'ok' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">سياسة كلمة المرور</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full" 
                              :class="systemStatus.password_policy === 'ok' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <span x-text="systemStatus.password_policy === 'ok' ? 'ممتاز' : 'غير متوافق'"></span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" :class="systemStatus.api_security === 'ok' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">أمان الـ API</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full" 
                              :class="systemStatus.api_security === 'ok' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <span x-text="systemStatus.api_security === 'ok' ? 'ممتاز' : 'غير متوافق'"></span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" :class="systemStatus.session_security === 'ok' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-700'">أمان الجلسات</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full" 
                              :class="systemStatus.session_security === 'ok' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <span x-text="systemStatus.session_security === 'ok' ? 'ممتاز' : 'غير متوافق'"></span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" :class="systemStatus.encryption_status === 'ok' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">التشفير</span>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full" 
                              :class="systemStatus.encryption_status === 'ok' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                            <span x-text="systemStatus.encryption_status === 'ok' ? 'ممتاز' : 'غير متوافق'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Tokens Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">توكنات الـ API</h3>
                <button onclick="showNotification('إنشاء توكن API جديد', 'info')" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all hover-scale">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linepath="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        توكن جديد
                    </span>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Token Statistics -->
                <div class="p-4 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                    <h4 class="font-medium mb-3" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">إحصائيات التوكنات</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">إجمالي التوكنات:</span>
                            <span class="text-sm font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="tokenStats.total_tokens || '0'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التوكنات النشطة:</span>
                            <span class="text-sm font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="tokenStats.active_tokens || '0'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">التوكنات المنتهية:</span>
                            <span class="text-sm font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="tokenStats.expired_tokens || '0'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm" :text-darkMode ? 'text-gray-400' : 'text-gray-600'">غير نشطة:</span>
                            <span class="text-sm font-medium" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="tokenStats.inactive_tokens || '0'"></span>
                        </div>
                    </div>
                </div>

                <!-- Recent Tokens -->
                <div class="p-4 rounded-lg" :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                    <h4 class="font-medium mb-3" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">التوكنات الحديثة</h4>
                    <div class="space-y-2">
                        <template x-for="token in recentTokens" :key="token.id">
                            <div class="flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold ml-3">
                                        <span x-text="token.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium" :class="darkMode ? 'text-gray-200' : 'text-gray-900'" x-text="token.name"></div>
                                        <div class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-500'" x-text="token.formatted_token"></div>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-green-100 text-green-800': token.status === 'active',
                                              'bg-gray-100 text-gray-800': token.status === 'inactive',
                                              'bg-red-100 text-red-800': token.status === 'expired'
                                          }"
                                          x-text="token.status_text"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Audit Log -->
        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">سجل التدقيق الأمني</h3>
                <button onclick="showNotification('تصدير سجل التدقيق', 'info')" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all hover-scale">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        تصدير
                    </span>
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead :class="darkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                الحدث
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                النوع
                            </th>
                            <th class="px-6 3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                المستخدم
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                العنوان IP
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="darkMode ? 'text-gray-300' : 'text-gray-500'">
                                التاريخ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <template x-for="log in auditLogs" :key="log.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="log.event_type"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full" 
                                          :class="{
                                              'bg-blue-100 text-blue-800': log.type === 'info',
                                              'bg-green-100 text-green-800': log.type === 'success',
                                              'bg-yellow-100 text-yellow-800': log.type === 'warning',
                                              'bg-red-100 text-red-800': log.type === 'error'
                                          }"
                                          x-text="log.type_text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="log.user_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="log.ip_address"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-gray-900'" x-text="log.created_at"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6" :class="darkMode ? 'bg-gray-800' : ''">
            <h3 class="text-lg font-semibold mb-4" :class="darkMode ? 'text-gray-200' : 'text توض علام" : 'text-gray-800'">إجراءات أمان سريعة</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button @click="generateToken()" 
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012-2h4m0 0V5a2 2 0 012-2h-4m0 0a2 2 0 00-2-2V5a2 2 0 00-2-2h-4m0 0a2 2 0 00-2-2V5a2 2 0 00-2-2h-4m0 0a2 2 0 00-2-2V5a2 2 0 00-2-2h-4m-6 9a2 2 0 002-2h2a2 2 0 002 2v2a2 2 0 002 2h2a2 2 0 002-2v-2z"></path>
                    </svg>
                    توليد توكن
                </button>
                <button @click="validatePassword()" 
                        class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    فحص كلمة المرور
                </button>
                <button @click="runSecurityCheck()" 
                        class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.657-2.929a1 1 0 00-.707-.293l-5.414-5.414a1 1 0 00-1.414 0l-5.414 5.414a1 1 0 001.414 0z"></path>
                    </svg>
                    فحص أمان
                </button>
            </div>
        </div>
    </div>

    <script>
        function securityPage() {
            return {
                darkMode: false,
                securityScore: 85,
                systemStatus: {
                    password_policy: 'ok',
                    api_security: 'ok',
                    session_security: 'ok',
                    encryption_status: 'ok'
                },
                tokenStats: {
                    total_tokens: 0,
                    active_tokens: 0,
                    expired_tokens: 0,
                    inactive_tokens: 0
                },
                recentTokens: [],
                auditLogs: [],
                loading: false,
                
                init() {
                    this.darkMode = document.runMode === 'dark-mode';
                    this.loadSecurityStatus();
                    this.loadTokenStats();
                    this.loadRecentTokens();
                    this.loadAuditLogs();
                },
                
                async loadSecurityStatus() {
                    try {
                        const response = await fetch('/api/security/status');
                        this.systemStatus = await response.json();
                        this.calculateSecurityScore();
                    } catch (error) {
                        console.error('Failed to load security status:', error);
                    }
                },
                
                calculateSecurityScore() {
                    let score = 100;
                    
                    if (this.systemStatus.password_policy !== 'ok') score -= 20;
                    if (this.systemStatus.api_security !== 'ok') score -= 20;
                    if (this.systemStatus.session_security !== 'ok') score -= 20;
                    if (this.systemStatus.encryption_status !== 'ok') score -= 20;
                    
                    this.securityScore = Math.max(0, score);
                },
                
                async loadTokenStats() {
                    try {
                        const response = await fetch('/api/tokens/statistics');
                        this.tokenStats = await response.json();
                    } catch (error) {
                        console.error('Failed to load token statistics:', error);
                    }
                },
                
                async loadRecentTokens() {
                    try {
                        const response = await fetch('/api/tokens/recent');
                        const data = await response.json();
                        this.recentTokens = data.tokens.map(token => ({
                            ...token,
                            status_text: this.getStatusText(token.status),
                            formatted_token: this.formatToken(token.token)
                        }));
                    } catch (error) {
                        console.error('Failed to load recent tokens:', error);
                    }
                },
                
                async loadAuditLogs() {
                    try {
                        const response = await fetch('/api/security/audit-log');
                        const data = await response.json();
                        this.auditLogs = data.logs.map(log => ({
                            ...log,
                            type_text: this.getEventTypeText(log.event_type),
                            created_at: this.formatDateTime(log.created_at)
                        }));
                    } catch (error) {
                        console.error('Failed to load audit logs:', error);
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
                
                getEventTypeText(type) {
                    switch(type) {
                        case 'login': return 'تسجيل دخول';
                        case 'logout': return 'تسجيل خروج';
                        case 'password_change': return 'تغيير كلمة المرور';
                        case 'token_created': return 'إنشاء توكن';
                        case 'token_revoked': 'إلغاء توكن';
                        case 'api_access': return 'وصول للـ API';
                        case 'security_alert': return 'تنبيه أمني';
                        default: return type;
                    }
                },
                
                formatToken(token) {
                    if (!token) return '';
                    return token.substring(0, 8) + '...' + token.substring(token.length - 8);
                },
                
                formatDateTime(date) {
                    return new Date(date).toLocaleString('ar-SA');
                },
                
                async generateToken() {
                    try {
                        const response = await fetch('/api/security/generate-token', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const result = await response.json();
                            this.$root.showNotification(`تم إنشاء توكن جديد: ${result.token}`, 'success');
                            this.loadTokenStats();
                            this.loadRecentTokens();
                        } else {
                            throw new Error('Failed to generate token');
                        }
                    } catch (error) {
                        console.error('Failed to generate token:', error);
                        this.$root.showNotification('فشل إنشاء التوكن', 'error');
                    }
                },
                
                async validatePassword() {
                    this.$root.showNotification('فتحص كلمة المرور', 'info');
                    // Open password validation modal
                },
                
                async runSecurityCheck() {
                    this.$root.showNotification('بدء فحص الأمان الشامل', 'info');
                    try {
                        const response = await fetch('/api/security/check', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const result = await response.json();
                            this.systemStatus = result.system_status;
                            this.calculateSecurityScore();
                            this.$root.showNotification('اكتمل فحص الأمان', 'success');
                        } else {
                            throw new Error('Security check failed');
                        }
                    } catch (error) {
                        console.error('Failed to run security check:', error);
                        this.$root.showNotification('فشل فحص الأمان', 'error');
                    }
                }
            }
        }
    </script>
@endsection
