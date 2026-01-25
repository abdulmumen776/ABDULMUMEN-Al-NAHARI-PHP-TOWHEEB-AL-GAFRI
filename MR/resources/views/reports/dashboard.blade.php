<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير لوحة التحكم</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            direction: rtl;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .stat {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
            min-width: 150px;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            margin-top: 10px;
        }
        .section {
            margin: 30px 0;
        }
        .section h2 {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: right;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير لوحة التحكم</h1>
        <p>تاريخ الإنشاء: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>الإحصائيات الرئيسية</h2>
        <div class="stats">
            <div class="stat">
                <div class="stat-value">{{ $totalClients }}</div>
                <div class="stat-label">إجمالي العملاء</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $activeOperations }}</div>
                <div class="stat-label">العمليات النشطة</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $monitoredApis }}</div>
                <div class="stat-label">الـ APIs المراقبة</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $openAlerts }}</div>
                <div class="stat-label">التنبيهات المفتوحة</div>
            </div>
        </div>
    </div>

    @if(!empty($performanceData))
    <div class="section">
        <h2>بيانات الأداء</h2>
        
        @if(!empty($performanceData['response_time']))
        <h3>وقت الاستجابة</h3>
        <table>
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>وقت الاستجابة (ms)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceData['response_time'] as $data)
                <tr>
                    <td>{{ $data['date'] ?? 'N/A' }}</td>
                    <td>{{ $data['value'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if(!empty($performanceData['success_rate']))
        <h3>معدل النجاح</h3>
        <table>
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>معدل النجاح (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceData['success_rate'] as $data)
                <tr>
                    <td>{{ $data['date'] ?? 'N/A' }}</td>
                    <td>{{ $data['value'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام المراقبة المتقدم</p>
        <p>جميع الحقوق محفوظة © {{ date('Y') }}</p>
    </div>
</body>
</html>
