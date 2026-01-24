<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerformanceMetricController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ApiTokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Web Routes (for frontend data fetching)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard API
    Route::get('/dashboard/statistics', function () {
        return response()->json([
            'total_clients' => 150,
            'active_operations' => 45,
            'monitored_apis' => 12,
            'open_alerts' => 8,
        ]);
    });

    // Clients API
    Route::prefix('clients')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'clients' => [
                    [
                        'id' => 1,
                        'name' => 'شركة الأمل للتجارة',
                        'contact_email' => 'info@alaml.com',
                        'contact_phone' => '+966 50 123 4567',
                        'status' => 'active',
                        'industry' => 'تجارة',
                        'operations_count' => 25,
                        'apis_count' => 5,
                        'created_at' => '2024-01-15',
                    ],
                    [
                        'id' => 2,
                        'name' => 'شركة النور للخدمات',
                        'contact_email' => 'info@noor.com',
                        'contact_phone' => '+966 50 987 6543',
                        'status' => 'active',
                        'industry' => 'خدمات',
                        'operations_count' => 18,
                        'apis_count' => 3,
                        'created_at' => '2024-01-20',
                    ],
                    [
                        'id' => 3,
                        'name' => 'شركة الرائد للتكنولوجيا',
                        'contact_email' => 'info@raed.com',
                        'contact_phone' => '+966 50 456 7890',
                        'status' => 'inactive',
                        'industry' => 'تكنولوجيا',
                        'operations_count' => 12,
                        'apis_count' => 8,
                        'created_at' => '2024-02-01',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_clients' => 3,
                'active_clients' => 2,
                'total_operations' => 55,
                'total_apis' => 16,
            ]);
        });
    });

    // Operations API
    Route::prefix('operations')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'operations' => [
                    [
                        'id' => 1,
                        'name' => 'مراقبة أداء النظام',
                        'description' => 'مراقبة مستمرة لأداء النظام',
                        'client_name' => 'شركة الأمل للتجارة',
                        'type' => 'monitoring',
                        'status' => 'active',
                        'scheduled_at' => '2024-01-15 10:00',
                        'duration' => '2 ساعة',
                        'created_at' => '2024-01-15',
                    ],
                    [
                        'id' => 2,
                        'name' => 'تحليل البيانات',
                        'description' => 'تحليل شامل للبيانات',
                        'client_name' => 'شركة النور للخدمات',
                        'type' => 'analysis',
                        'status' => 'completed',
                        'scheduled_at' => '2024-01-16 14:00',
                        'duration' => '1 ساعة',
                        'created_at' => '2024-01-16',
                    ],
                    [
                        'id' => 3,
                        'name' => 'صيانة دورية',
                        'description' => 'صيانة دورية للنظام',
                        'client_name' => 'شركة الرائد للتكنولوجيا',
                        'type' => 'maintenance',
                        'status' => 'scheduled',
                        'scheduled_at' => '2024-01-17 09:00',
                        'duration' => '30 دقيقة',
                        'created_at' => '2024-01-17',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_operations' => 3,
                'active_operations' => 1,
                'completed_operations' => 1,
                'success_rate' => '85%',
            ]);
        });
    });

    // APIs API
    Route::prefix('apis')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'apis' => [
                    [
                        'id' => 1,
                        'name' => 'API المستخدمين',
                        'base_url' => 'https://api.example.com/users',
                        'client_name' => 'شركة الأمل للتجارة',
                        'type' => 'rest',
                        'status' => 'active',
                        'avg_response_time' => 120,
                        'success_rate' => 95,
                        'created_at' => '2024-01-15',
                    ],
                    [
                        'id' => 2,
                        'name' => 'API المنتجات',
                        'base_url' => 'https://api.example.com/products',
                        'client_name' => 'شركة النور للخدمات',
                        'type' => 'graphql',
                        'status' => 'monitored',
                        'avg_response_time' => 85,
                        'success_rate' => 98,
                        'created_at' => '2024-01-20',
                    ],
                    [
                        'id' => 3,
                        'name' => 'API الطلبات',
                        'base_url' => 'https://api.example.com/orders',
                        'client_name' => 'شركة الرائد للتكنولوجيا',
                        'type' => 'soap',
                        'status' => 'error',
                        'avg_response_time' => 250,
                        'success_rate' => 75,
                        'created_at' => '2024-02-01',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_apis' => 3,
                'active_apis' => 2,
                'monitored_apis' => 1,
                'error_rate' => '5%',
            ]);
        });

        Route::post('/{id}/test', function ($id) {
            return response()->json([
                'response_time' => rand(50, 200),
                'status_code' => 200,
                'success' => true,
                'message' => 'API test successful'
            ]);
        });
    });

    // Dashboards API
    Route::prefix('dashboards')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'dashboards' => [
                    [
                        'id' => 1,
                        'name' => 'لوحة التحكم الرئيسية',
                        'description' => 'لوحة تحكم رئيسية للنظام',
                        'type' => 'performance',
                        'status' => 'active',
                        'widgets_count' => 8,
                        'views_count' => 150,
                        'created_by' => 'المسؤول',
                        'updated_at' => '2024-01-20',
                    ],
                    [
                        'id' => 2,
                        'name' => 'لوحة التحليلات',
                        'description' => 'لوحة تحليلات البيانات',
                        'type' => 'analytics',
                        'status' => 'active',
                        'widgets_count' => 6,
                        'views_count' => 85,
                        'created_by' => 'المسؤول',
                        'updated_at' => '2024-01-18',
                    ],
                    [
                        'id' => 3,
                        'name' => 'لوحة المراقبة',
                        'description' => 'لوحة مراقبة الأداء',
                        'type' => 'monitoring',
                        'status' => 'draft',
                        'widgets_count' => 4,
                        'views_count' => 25,
                        'created_by' => 'المسؤول',
                        'updated_at' => '2024-01-22',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_dashboards' => 3,
                'active_dashboards' => 2,
                'total_widgets' => 18,
                'daily_views' => 45,
            ]);
        });

        Route::post('/{id}/duplicate', function ($id) {
            return response()->json([
                'success' => true,
                'message' => 'Dashboard duplicated successfully'
            ]);
        });
    });

    // Alerts API
    Route::prefix('alerts')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'alerts' => [
                    [
                        'id' => 1,
                        'title' => 'ارتفاع استخدام الذاكرة',
                        'message' => 'استخدام الذاكرة تجاوز 80%',
                        'severity' => 'high',
                        'type' => 'performance',
                        'status' => 'open',
                        'created_at' => '2024-01-20 10:30',
                    ],
                    [
                        'id' => 2,
                        'title' => 'محاولة تسجيل دخول غير مصرح',
                        'message' => 'تم اكتشاف محاولة تسجيل دخول من عنوان IP غير معروف',
                        'severity' => 'critical',
                        'type' => 'security',
                        'status' => 'open',
                        'created_at' => '2024-01-20 11:15',
                    ],
                    [
                        'id' => 3,
                        'title' => 'فشل في API',
                        'message' => 'API المستخدمين يعود خطأ 500',
                        'severity' => 'medium',
                        'type' => 'api',
                        'status' => 'acknowledged',
                        'created_at' => '2024-01-20 12:00',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_alerts' => 3,
                'open_alerts' => 2,
                'critical_alerts' => 1,
                'resolved_today' => 5,
            ]);
        });

        Route::post('/{id}/acknowledge', function ($id) {
            return response()->json([
                'success' => true,
                'message' => 'Alert acknowledged successfully'
            ]);
        });

        Route::post('/{id}/resolve', function ($id) {
            return response()->json([
                'success' => true,
                'message' => 'Alert resolved successfully'
            ]);
        });

        Route::post('/{id}/dismiss', function ($id) {
            return response()->json([
                'success' => true,
                'message' => 'Alert dismissed successfully'
            ]);
        });
    });

    // Security API
    Route::prefix('security')->group(function () {
        Route::get('/status', function () {
            return response()->json([
                'password_policy' => 'ok',
                'api_security' => 'ok',
                'session_security' => 'ok',
                'encryption_status' => 'ok',
            ]);
        });

        Route::get('/audit-log', function () {
            return response()->json([
                'logs' => [
                    [
                        'id' => 1,
                        'event_type' => 'login',
                        'type' => 'success',
                        'user_name' => 'المسؤول',
                        'ip_address' => '192.168.1.100',
                        'created_at' => '2024-01-20 09:00',
                    ],
                    [
                        'id' => 2,
                        'event_type' => 'password_change',
                        'type' => 'info',
                        'user_name' => 'المسؤول',
                        'ip_address' => '192.168.1.100',
                        'created_at' => '2024-01-20 10:30',
                    ],
                    [
                        'id' => 3,
                        'event_type' => 'token_created',
                        'type' => 'info',
                        'user_name' => 'المسؤول',
                        'ip_address' => '192.168.1.100',
                        'created_at' => '2024-01-20 11:00',
                    ],
                ]
            ]);
        });

        Route::post('/generate-token', function () {
            return response()->json([
                'token' => 'mr_' . bin2hex(random_bytes(32)),
                'message' => 'Token generated successfully'
            ]);
        });

        Route::post('/check', function () {
            return response()->json([
                'system_status' => [
                    'password_policy' => 'ok',
                    'api_security' => 'ok',
                    'session_security' => 'ok',
                    'encryption_status' => 'ok',
                ],
                'security_score' => 85
            ]);
        });
    });

    // Tokens API
    Route::prefix('tokens')->group(function () {
        Route::get('/', function () {
            return response()->json([
                'tokens' => [
                    [
                        'id' => 1,
                        'name' => 'Token الرئيسي',
                        'token' => 'mr_' . bin2hex(random_bytes(32)),
                        'abilities' => ['*'],
                        'status' => 'active',
                        'created_at' => '2024-01-20',
                        'expires_at' => '2024-04-20',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Token API',
                        'token' => 'mr_' . bin2hex(random_bytes(32)),
                        'abilities' => ['apis:read', 'apis:write'],
                        'status' => 'active',
                        'created_at' => '2024-01-18',
                        'expires_at' => '2024-04-18',
                    ],
                ]
            ]);
        });

        Route::get('/statistics', function () {
            return response()->json([
                'total_tokens' => 2,
                'active_tokens' => 2,
                'expired_tokens' => 0,
                'inactive_tokens' => 0,
            ]);
        });

        Route::get('/recent', function () {
            return response()->json([
                'tokens' => [
                    [
                        'id' => 1,
                        'name' => 'Token الرئيسي',
                        'token' => 'mr_' . bin2hex(random_bytes(32)),
                        'status' => 'active',
                        'created_at' => '2024-01-20',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Token API',
                        'token' => 'mr_' . bin2hex(random_bytes(32)),
                        'status' => 'active',
                        'created_at' => '2024-01-18',
                    ],
                ]
            ]);
        });
    });

});
