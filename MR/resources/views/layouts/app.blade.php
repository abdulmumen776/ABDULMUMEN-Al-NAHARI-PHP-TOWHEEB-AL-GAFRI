<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="نظام المراقبة المتقدم - لوحة تحكم شاملة لإدارة العملاء والعمليات والـ APIs">
    <meta name="keywords" content="مراقبة, لوحة تحكم, إدارة, عملاء, عمليات, APIs">
    <meta name="author" content="نظام المراقبة">
    <title>@yield('title', 'نظام المراقبة')</title>
    
    <!-- Google Fonts - Cairo for Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- Custom CSS -->
    <style>
        /* RTL Support */
        body {
            font-family: 'Cairo', sans-serif;
            scroll-behavior: smooth;
        }
        
        /* CSS Variables for Professional Theme */
        :root {
            /* Light Theme */
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-200: #bfdbfe;
            --primary-300: #93c5fd;
            --primary-400: #60a5fa;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;
            
            --secondary-50: #f8fafc;
            --secondary-100: #f1f5f9;
            --secondary-200: #e2e8f0;
            --secondary-300: #cbd5e1;
            --secondary-400: #94a3b8;
            --secondary-500: #64748b;
            --secondary-600: #475569;
            --secondary-700: #334155;
            --secondary-800: #1e293b;
            --secondary-900: #0f172a;
            
            --success-50: #f0fdf4;
            --success-100: #dcfce7;
            --success-200: #bbf7d0;
            --success-300: #86efac;
            --success-400: #4ade80;
            --success-500: #22c55e;
            --success-600: #16a34a;
            --success-700: #15803d;
            --success-800: #166534;
            --success-900: #14532d;
            
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-200: #fde68a;
            --warning-300: #fcd34d;
            --warning-400: #fbbf24;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-700: #b45309;
            --warning-800: #92400e;
            --warning-900: #78350f;
            
            --danger-50: #fef2f2;
            --danger-100: #fee2e2;
            --danger-200: #fecaca;
            --danger-300: #fca5a5;
            --danger-400: #f87171;
            --danger-500: #ef4444;
            --danger-600: #dc2626;
            --danger-700: #b91c1c;
            --danger-800: #991b1b;
            --danger-900: #7f1d1d;
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #111827;
            --text-secondary: #374151;
            --text-tertiary: #6b7280;
            --text-quaternary: #9ca3af;
            --border-primary: #e5e7eb;
            --border-secondary: #d1d5db;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Dark Theme */
        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f9fafb;
            --text-secondary: #e2e8f0;
            --text-tertiary: #cbd5e1;
            --text-quaternary: #94a3b8;
            --border-primary: #374151;
            --border-secondary: #4b5563;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        /* Professional Typography */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-400) 50%, var(--primary-200) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        .text-gradient-success {
            background: linear-gradient(135deg, var(--success-600) 0%, var(--success-400) 50%, var(--success-200) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        .text-gradient-warning {
            background: linear-gradient(135deg, var(--warning-600) 0%, var(--warning-400) 50%, var(--warning-200) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        .text-gradient-danger {
            background: linear-gradient(135deg, var(--danger-600) 0%, var(--danger-400) 50%, var(--danger-200) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        /* Professional Cards */
        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-primary);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
            transition: width 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-200);
        }
        
        .card:hover::before {
            width: 100%;
        }
        
        .card-success::before {
            background: linear-gradient(90deg, var(--success-500), var(--success-600));
        }
        
        .card-warning::before {
            background: linear-gradient(90deg, var(--warning-500), var(--warning-600));
        }
        
        .card-danger::before {
            background: linear-gradient(90deg, var(--danger-500), var(--danger-600));
        }
        
        /* Professional Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            transform: translateX(-100%);
        }
        
        .btn:hover::before {
            transform: translateX(0);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-500));
            color: white;
            border-color: var(--primary-600);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-700), var(--primary-600));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-600), var(--success-500));
            color: white;
            border-color: var(--success-600);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, var(--success-700), var(--success-600));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-600), var(--danger-500));
            color: white;
            border-color: var(--danger-600);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, var(--danger-700), var(--danger-600));
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary-600);
            border-color: var(--primary-600);
        }
        
        .btn-outline:hover {
            background: var(--primary-600);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        /* Professional Sidebar */
        .sidebar {
            background: var(--bg-primary);
            border-right: 1px solid var(--border-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, transparent, var(--primary-200), transparent);
        }
        
        .sidebar.collapsed {
            width: 4rem;
        }
        
        /* Professional Navigation */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background: var(--primary-500);
            transition: width 0.3s ease;
            opacity: 0.1;
        }
        
        .nav-item:hover {
            color: var(--primary-600);
            background: var(--primary-50);
            transform: translateX(4px);
        }
        
        .nav-item:hover::before {
            width: 100%;
        }
        
        .nav-item.active {
            color: var(--primary-600);
            background: var(--primary-100);
            font-weight: 600;
            transform: translateX(4px);
        }
        
        .nav-item.active::before {
            width: 100%;
            opacity: 0.2;
        }
        
        /* Professional Notifications */
        .notification {
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-primary);
            border: 1px solid var(--border-primary);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }
        
        .notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-500);
        }
        
        .notification.success::before {
            background: var(--success-500);
        }
        
        .notification.warning::before {
            background: var(--warning-500);
        }
        
        .notification.error::before {
            background: var(--danger-500);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Professional Loading Spinner */
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-primary);
            border-top: 3px solid var(--primary-500);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Professional Tables */
        .table {
            background: var(--bg-primary);
            border: 1px solid var(--border-primary);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table thead {
            background: var(--bg-secondary);
        }
        
        .table th {
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-primary);
        }
        
        .table td {
            padding: 1rem;
            text-align: right;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-primary);
        }
        
        .table tbody tr:hover {
            background: var(--bg-secondary);
        }
        
        /* Professional Forms */
        .form-input {
            background: var(--bg-primary);
            border: 1px solid var(--border-primary);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-input::placeholder {
            color: var(--text-quaternary);
        }
        
        /* Professional Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
            transition: all 0.2s ease;
        }
        
        .badge-primary {
            background: var(--primary-100);
            color: var(--primary-800);
        }
        
        .badge-success {
            background: var(--success-100);
            color: var(--success-800);
        }
        
        .badge-warning {
            background: var(--warning-100);
            color: var(--warning-800);
        }
        
        .badge-danger {
            background: var(--danger-100);
            color: var(--danger-800);
        }
        
        /* Professional Progress Bar */
        .progress {
            background: var(--bg-tertiary);
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 0.5rem;
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
            transition: width 0.3s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                z-index: 50;
                transform: translateX(100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0;
            }
        }
        
        /* Accessibility Improvements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Focus Styles */
        *:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
        }
        
        /* Skip Link */
        .skip-link {
            position: absolute;
            top: -40px;
            right: 0;
            background: var(--primary-600);
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 100;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .sidebar {
                display: none !important;
            }
            
            .main-content {
                margin: 0 !important;
            }
            
            .card {
                break-inside: avoid;
            }
        }
    </style>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-sans antialiased font-arabic bg-gray-50" x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarOpen: window.innerWidth >= 768,
        notifications: [],
        loading: false,
        init() {
            this.$watch('darkMode', (value) => {
                localStorage.setItem('darkMode', value);
                if (value) {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
            });
            if (this.darkMode) {
                document.body.classList.add('dark-mode');
            }
            this.loadNotifications();
        },
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        async loadNotifications() {
            try {
                const response = await fetch('/api/notifications');
                this.notifications = await response.json();
            } catch (error) {
                console.error('Failed to load notifications:', error);
            }
        },
        showNotification(message, type = 'info') {
            const notification = { id: Date.now(), message, type, show: true };
            this.notifications.push(notification);
            setTimeout(() => {
                notification.show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== notification.id);
                }, 300);
            }, 5000);
        }
    }" x-cloak>
        <div class="min-h-screen" :class="darkMode ? 'bg-gray-900' : 'bg-gray-50'">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 right-0 z-50 w-64 glass-effect sidebar-transition transform" 
                 :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'"
                 x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                <div class="flex flex-col h-full">
                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between p-4 border-b" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <h2 class="text-xl font-bold text-gradient">نظام المراقبة</h2>
                        <button @click="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Sidebar Navigation -->
                    <nav class="flex-1 p-4 space-y-2" x-data="{ openDropdown: null }">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                            <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">لوحة التحكم</span>
                        </a>

                        <!-- Clients Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'clients' ? null : 'clients'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">العملاء</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'clients'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'clients'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة العملاء
                                </a>
                                <a href="{{ route('clients.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إضافة عميل جديد
                                </a>
                            </div>
                        </div>

                        <!-- Operations Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'operations' ? null : 'operations'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">العمليات</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'operations'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'operations'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('operations.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة العمليات
                                </a>
                                <a href="{{ route('operations.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إضافة عملية جديدة
                                </a>
                            </div>
                        </div>

                        <!-- APIs Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'apis' ? null : 'apis'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">الـ APIs</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'apis'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'apis'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('apis.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة الـ APIs
                                </a>
                                <a href="{{ route('apis.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إضافة API جديد
                                </a>
                            </div>
                        </div>

                        <!-- Dashboards Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'dashboards' ? null : 'dashboards'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">لوحات التحكم</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'dashboards'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'dashboards'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('dashboards.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة لوحات التحكم
                                </a>
                                <a href="{{ route('dashboards.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إنشاء لوحة تحكم جديدة
                                </a>
                            </div>
                        </div>

                        <!-- Alerts Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'alerts' ? null : 'alerts'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">التنبيهات</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'alerts'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'alerts'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('alerts.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة التنبيهات
                                </a>
                                <a href="{{ route('alerts.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إنشاء تنبيه جديد
                                </a>
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                        
                        <!-- Security Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'security' ? null : 'security'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h12zM4 10h14M4 6h14"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">الأمان</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'security'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'security'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('security.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة إعدادات الأمان
                                </a>
                                <a href="{{ route('security.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إنشاء إعداد أمان جديد
                                </a>
                            </div>
                        </div>

                        <!-- Tokens Dropdown -->
                        <div class="relative">
                            <button @click="openDropdown = openDropdown === 'tokens' ? null : 'tokens'" 
                                    class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 transition-all group">
                                <svg class="w-5 h-5 ml-3 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h2a5 5 0 013.9 8.1L15 17M7 7h2a5 5 0 00-3.9 8.1L9 17"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 font-medium">التوكنات</span>
                                <svg class="w-4 h-4 mr-auto text-gray-400" :class="{'rotate-180': openDropdown === 'tokens'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openDropdown === 'tokens'" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <a href="{{ route('tokens.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-t-lg">
                                    قائمة التوكنات
                                </a>
                                <a href="{{ route('tokens.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900">
                                    إنشاء توكن جديد
                                </a>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Sidebar Footer -->
                    <div class="p-4 border-t" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                        <div class="flex items-center justify-between">
                            <span class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                                {{ Auth::user()->name }}
                            </span>
                            <button @click="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1" :class="sidebarOpen ? 'mr-64' : ''">
                <!-- Top Navigation -->
                <header class="glass-effect border-b" :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
                    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex items-center">
                            <button @click="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <h1 class="text-2xl font-bold mr-4 text-gradient">{{ $header ?? 'لوحة التحكم' }}</h1>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 relative">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full notification-badge"></span>
                                </button>
                            </div>
                            
                            <!-- User Menu -->
                            <div class="relative">
                                <button class="flex items-center p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ Auth::user()->name[0] }}
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-4 sm:p-6 lg:p-8">
                    <!-- Breadcrumbs -->
                    @if(isset($breadcrumbs) && !empty($breadcrumbs))
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    لوحة التحكم
                                </a>
                            </li>
                            @foreach($breadcrumbs as $index => $breadcrumb)
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        @if($loop->last)
                                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $breadcrumb['title'] }}</span>
                                        @else
                                            <a href="{{ $breadcrumb['url'] ?? '#' }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-blue-400">{{ $breadcrumb['title'] }}</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                    @endif

                    <!-- Notifications -->
                    <div class="fixed top-20 left-4 z-50 space-y-2">
                        <template x-for="notification in notifications" :key="notification.id">
                            <div x-show="notification.show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-x-full"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-x-0"
                                 x-transition:leave-end="opacity-0 transform translate-x-full"
                                 class="p-4 rounded-lg shadow-lg max-w-sm"
                                 :class="{
                                     'bg-green-500 text-white': notification.type === 'success',
                                     'bg-red-500 text-white': notification.type === 'error',
                                     'bg-yellow-500 text-white': notification.type === 'warning',
                                     'bg-blue-500 text-white': notification.type === 'info'
                                 }">
                                <p x-text="notification.message"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Loading Overlay -->
                    <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="loading-spinner"></div>
                    </div>

                    <!-- Page Content -->
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
