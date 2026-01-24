<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\PerformanceMetric;
use App\Models\PatternAnalysisResult;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AlertManagementService
{
    /**
     * Process 6.1: Manage Alerts
     */
    public function manageAlerts(array $identifiedPatterns, array $performanceMetrics = []): array
    {
        try {
            $alerts = [];

            // Generate alerts from identified patterns
            $patternAlerts = $this->generateAlertsFromPatterns($identifiedPatterns);
            $alerts = array_merge($alerts, $patternAlerts);

            // Generate alerts from performance metrics
            $metricAlerts = $this->generateAlertsFromMetrics($performanceMetrics);
            $alerts = array_merge($alerts, $metricAlerts);

            // Process and store alerts
            $processedAlerts = $this->processAlerts($alerts);

            Log::info('Alert management completed', [
                'patterns_processed' => count($identifiedPatterns),
                'metrics_processed' => count($performanceMetrics),
                'alerts_generated' => count($processedAlerts)
            ]);

            return $processedAlerts;

        } catch (\Exception $e) {
            Log::error('Alert management failed', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Generate alerts from identified patterns
     */
    private function generateAlertsFromPatterns(array $patterns): array
    {
        $alerts = [];

        foreach ($patterns as $pattern) {
            if ($this->shouldGenerateAlert($pattern)) {
                $alerts[] = $this->createAlertFromPattern($pattern);
            }
        }

        return $alerts;
    }

    /**
     * Generate alerts from performance metrics
     */
    private function generateAlertsFromMetrics(array $metrics): array
    {
        $alerts = [];

        foreach ($metrics as $metric) {
            if ($this->shouldGenerateMetricAlert($metric)) {
                $alerts[] = $this->createAlertFromMetric($metric);
            }
        }

        return $alerts;
    }

    /**
     * Determine if alert should be generated from pattern
     */
    private function shouldGenerateAlert(array $pattern): bool
    {
        $severity = $pattern['severity'] ?? 'low';
        $confidence = $pattern['confidence'] ?? 0;

        // Generate alert for high severity patterns
        if ($severity === 'high') {
            return true;
        }

        // Generate alert for medium severity with high confidence
        if ($severity === 'medium' && $confidence >= 0.7) {
            return true;
        }

        // Generate alert for low severity with very high confidence
        if ($severity === 'low' && $confidence >= 0.9) {
            return true;
        }

        return false;
    }

    /**
     * Determine if alert should be generated from metric
     */
    private function shouldGenerateMetricAlert(array $metric): bool
    {
        $status = $metric['status'] ?? 'normal';
        $value = $metric['value'] ?? 0;
        $threshold = $metric['threshold'] ?? null;

        // Generate alert for critical or warning status
        if ($status === 'critical' || $status === 'warning') {
            return true;
        }

        // Generate alert if value exceeds threshold
        if ($threshold !== null && $value > $threshold) {
            return true;
        }

        return false;
    }

    /**
     * Create alert from pattern
     */
    private function createAlertFromPattern(array $pattern): array
    {
        return [
            'title' => $this->generateAlertTitle($pattern),
            'description' => $this->generateAlertDescription($pattern),
            'severity' => $this->mapPatternSeverityToAlertSeverity($pattern['severity'] ?? 'low'),
            'source_type' => 'pattern',
            'source_data' => $pattern,
            'recommendations' => $this->generatePatternRecommendations($pattern),
            'triggered_at' => now(),
        ];
    }

    /**
     * Create alert from metric
     */
    private function createAlertFromMetric(array $metric): array
    {
        return [
            'title' => $this->generateMetricAlertTitle($metric),
            'description' => $this->generateMetricAlertDescription($metric),
            'severity' => $this->mapMetricStatusToAlertSeverity($metric['status'] ?? 'normal'),
            'source_type' => 'metric',
            'source_data' => $metric,
            'recommendations' => $this->generateMetricRecommendations($metric),
            'triggered_at' => $metric['timestamp'] ?? now(),
        ];
    }

    /**
     * Process and store alerts
     */
    private function processAlerts(array $alerts): array
    {
        $processedAlerts = [];

        foreach ($alerts as $alertData) {
            try {
                // Check for similar existing alerts to avoid duplicates
                $existingAlert = $this->findSimilarAlert($alertData);

                if ($existingAlert) {
                    // Update existing alert
                    $this->updateExistingAlert($existingAlert, $alertData);
                    $processedAlerts[] = $existingAlert->fresh();
                } else {
                    // Create new alert
                    $alert = $this->createNewAlert($alertData);
                    $processedAlerts[] = $alert;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to process alert', [
                    'alert_title' => $alertData['title'] ?? 'Unknown',
                    'error' => $e->getMessage()
                ]);
                // Continue processing other alerts
            }
        }

        return $processedAlerts;
    }

    /**
     * Find similar existing alert
     */
    private function findSimilarAlert(array $alertData): ?Alert
    {
        $title = $alertData['title'] ?? '';
        $sourceType = $alertData['source_type'] ?? '';

        // Look for alerts with same title and source type in the last hour
        return Alert::where('title', $title)
            ->where('source_type', $sourceType)
            ->where('status', '!=', 'resolved')
            ->where('created_at', '>', now()->subHour())
            ->first();
    }

    /**
     * Update existing alert
     */
    private function updateExistingAlert(Alert $alert, array $alertData): void
    {
        $alert->update([
            'description' => $alertData['description'],
            'severity' => $alertData['severity'],
            'triggered_at' => $alertData['triggered_at'],
        ]);
    }

    /**
     * Create new alert
     */
    private function createNewAlert(array $alertData): Alert
    {
        return Alert::create([
            'performance_metric_id' => $this->extractMetricId($alertData),
            'title' => $alertData['title'],
            'description' => $alertData['description'],
            'severity' => $alertData['severity'],
            'status' => 'open',
            'triggered_at' => $alertData['triggered_at'],
        ]);
    }

    /**
     * Extract metric ID from alert data
     */
    private function extractMetricId(array $alertData): ?int
    {
        $sourceData = $alertData['source_data'] ?? [];
        
        if ($alertData['source_type'] === 'metric' && isset($sourceData['id'])) {
            return $sourceData['id'];
        }

        return null;
    }

    /**
     * Generate alert title from pattern
     */
    private function generateAlertTitle(array $pattern): string
    {
        $patternType = $pattern['type'] ?? 'unknown';
        $description = $pattern['description'] ?? 'Pattern detected';

        return ucfirst($patternType) . ': ' . $description;
    }

    /**
     * Generate alert description from pattern
     */
    private function generateAlertDescription(array $pattern): string
    {
        $insights = $pattern['insights'] ?? [];
        $metrics = $pattern['metrics'] ?? [];

        $description = 'Pattern analysis detected: ' . ($pattern['description'] ?? 'Unknown pattern');

        if (!empty($insights)) {
            $description .= '. Insights: ' . implode(', ', $insights);
        }

        if (!empty($metrics)) {
            $description .= '. Key metrics: ' . $this->formatMetricsForDescription($metrics);
        }

        return $description;
    }

    /**
     * Generate metric alert title
     */
    private function generateMetricAlertTitle(array $metric): string
    {
        $metricName = $metric['name'] ?? 'Unknown Metric';
        $status = $metric['status'] ?? 'normal';

        return $metricName . ' Alert (' . ucfirst($status) . ')';
    }

    /**
     * Generate metric alert description
     */
    private function generateMetricAlertDescription(array $metric): string
    {
        $metricName = $metric['name'] ?? 'Unknown Metric';
        $value = $metric['formatted_value'] ?? $metric['value'] ?? 0;
        $status = $metric['status'] ?? 'normal';
        $threshold = $metric['threshold'] ?? null;

        $description = "Metric {$metricName} is in {$status} status with value {$value}";

        if ($threshold !== null) {
            $description .= " (threshold: {$threshold})";
        }

        return $description;
    }

    /**
     * Map pattern severity to alert severity
     */
    private function mapPatternSeverityToAlertSeverity(string $patternSeverity): string
    {
        return match($patternSeverity) {
            'high' => 'critical',
            'medium' => 'medium',
            'low' => 'low',
            default => 'low',
        };
    }

    /**
     * Map metric status to alert severity
     */
    private function mapMetricStatusToAlertSeverity(string $metricStatus): string
    {
        return match($metricStatus) {
            'critical' => 'critical',
            'warning' => 'medium',
            'normal' => 'low',
            default => 'low',
        };
    }

    /**
     * Generate pattern recommendations
     */
    private function generatePatternRecommendations(array $pattern): array
    {
        $recommendations = [];
        $patternType = $pattern['type'] ?? 'unknown';
        $severity = $pattern['severity'] ?? 'low';

        switch ($patternType) {
            case 'response_time_pattern':
                $recommendations[] = 'Investigate slow API endpoints';
                $recommendations[] = 'Consider implementing caching';
                break;
            case 'error_rate_pattern':
                $recommendations[] = 'Review error logs for root cause';
                $recommendations[] = 'Implement better error handling';
                break;
            case 'availability_pattern':
                $recommendations[] = 'Check service health endpoints';
                $recommendations[] = 'Review infrastructure capacity';
                break;
            case 'performance_degradation':
                $recommendations[] = 'Analyze performance bottlenecks';
                $recommendations[] = 'Consider scaling resources';
                break;
            case 'resource_utilization':
                $recommendations[] = 'Monitor resource consumption';
                $recommendations[] = 'Optimize resource allocation';
                break;
            default:
                $recommendations[] = 'Review system performance';
                $recommendations[] = 'Monitor for continued issues';
        }

        if ($severity === 'high') {
            array_unshift($recommendations, 'Immediate investigation required');
        }

        return $recommendations;
    }

    /**
     * Generate metric recommendations
     */
    private function generateMetricRecommendations(array $metric): array
    {
        $recommendations = [];
        $metricName = $metric['name'] ?? 'Unknown Metric';
        $status = $metric['status'] ?? 'normal';

        switch ($metricName) {
            case 'cpu_usage':
                $recommendations[] = 'Monitor CPU-intensive processes';
                $recommendations[] = 'Consider scaling CPU resources';
                break;
            case 'memory_usage':
                $recommendations[] = 'Check for memory leaks';
                $recommendations[] = 'Optimize memory allocation';
                break;
            case 'disk_usage':
                $recommendations[] = 'Clean up unnecessary files';
                $recommendations[] = 'Expand storage capacity';
                break;
            default:
                $recommendations[] = 'Investigate metric performance';
                $recommendations[] = 'Review system configuration';
        }

        if ($status === 'critical') {
            array_unshift($recommendations, 'Immediate action required');
        }

        return $recommendations;
    }

    /**
     * Format metrics for description
     */
    private function formatMetricsForDescription(array $metrics): string
    {
        $formatted = [];

        foreach ($metrics as $key => $value) {
            if (is_numeric($value)) {
                $formatted[] = "{$key}: " . round($value, 2);
            } else {
                $formatted[] = "{$key}: {$value}";
            }
        }

        return implode(', ', array_slice($formatted, 0, 3)); // Limit to first 3 metrics
    }

    /**
     * Get active alerts
     */
    public function getActiveAlerts(): array
    {
        try {
            $alerts = Alert::where('status', '!=', 'resolved')
                ->with('performanceMetric')
                ->orderBy('severity', 'desc')
                ->orderBy('triggered_at', 'desc')
                ->get();

            return $alerts->toArray();

        } catch (\Exception $e) {
            Log::error('Failed to retrieve active alerts', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Resolve alert
     */
    public function resolveAlert(int $alertId, string $resolution = 'Manual resolution'): bool
    {
        try {
            $alert = Alert::findOrFail($alertId);
            
            $alert->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);

            Log::info('Alert resolved', [
                'alert_id' => $alertId,
                'resolution' => $resolution
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to resolve alert', [
                'alert_id' => $alertId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get alert statistics
     */
    public function getAlertStatistics(): array
    {
        try {
            $totalAlerts = Alert::count();
            $activeAlerts = Alert::where('status', '!=', 'resolved')->count();
            $criticalAlerts = Alert::where('severity', 'critical')->where('status', '!=', 'resolved')->count();
            $resolvedToday = Alert::where('status', 'resolved')
                ->whereDate('resolved_at', today())
                ->count();

            return [
                'total_alerts' => $totalAlerts,
                'active_alerts' => $activeAlerts,
                'critical_alerts' => $criticalAlerts,
                'resolved_today' => $resolvedToday,
                'alert_trend' => $this->calculateAlertTrend(),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get alert statistics', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Calculate alert trend
     */
    private function calculateAlertTrend(): string
    {
        try {
            $last24Hours = Alert::where('created_at', '>', now()->subDay())->count();
            $previous24Hours = Alert::where('created_at', '>', now()->subDays(2))
                ->where('created_at', '<=', now()->subDay())
                ->count();

            if ($last24Hours > $previous24Hours) {
                return 'increasing';
            } elseif ($last24Hours < $previous24Hours) {
                return 'decreasing';
            } else {
                return 'stable';
            }

        } catch (\Exception $e) {
            return 'unknown';
        }
    }
}
