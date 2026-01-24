<?php

namespace App\Services;

use App\Models\Dashboard;
use App\Models\PerformanceMetric;
use App\Models\PerformanceDataset;
use App\Models\PatternAnalysisResult;
use App\Models\DashboardVisualization;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    /**
     * Process 4.1: Format Metrics Data
     */
    public function formatMetricsData(array $metrics): array
    {
        try {
            $formattedMetrics = [];

            foreach ($metrics as $metric) {
                $formattedMetrics[] = [
                    'id' => $metric['id'] ?? null,
                    'name' => $metric['name'] ?? 'Unknown',
                    'value' => $metric['value'] ?? 0,
                    'unit' => $metric['unit'] ?? '',
                    'threshold' => $metric['threshold'] ?? null,
                    'status' => $this->determineMetricStatus($metric),
                    'trend' => $metric['trend'] ?? 'stable',
                    'formatted_value' => $this->formatValue($metric['value'] ?? 0, $metric['unit'] ?? ''),
                    'timestamp' => $metric['timestamp'] ?? now()->toISOString(),
                ];
            }

            Log::info('Metrics data formatted successfully', [
                'metrics_count' => count($formattedMetrics)
            ]);

            return $formattedMetrics;

        } catch (\Exception $e) {
            Log::error('Failed to format metrics data', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 4.2: Generate Dashboard Metrics
     */
    public function generateDashboardMetrics(array $formattedMetrics, array $identifiedPatterns = []): array
    {
        try {
            $dashboardMetrics = [
                'performance_metrics' => $formattedMetrics,
                'pattern_insights' => $this->processPatternInsights($identifiedPatterns),
                'health_indicators' => $this->generateHealthIndicators($formattedMetrics),
                'alerts_summary' => $this->generateAlertsSummary($formattedMetrics),
                'trend_analysis' => $this->analyzeTrends($formattedMetrics),
                'generated_at' => now()->toISOString(),
            ];

            Log::info('Dashboard metrics generated successfully', [
                'metrics_count' => count($formattedMetrics),
                'patterns_count' => count($identifiedPatterns)
            ]);

            return $dashboardMetrics;

        } catch (\Exception $e) {
            Log::error('Failed to generate dashboard metrics', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 4.3: Render Dashboard Components
     */
    public function renderDashboardComponents(array $dashboardMetrics, Dashboard $dashboard): array
    {
        try {
            $components = [];

            // Render performance charts
            $components['performance_charts'] = $this->renderPerformanceCharts($dashboardMetrics['performance_metrics']);

            // Render health indicators
            $components['health_indicators'] = $this->renderHealthIndicators($dashboardMetrics['health_indicators']);

            // Render pattern insights
            $components['pattern_insights'] = $this->renderPatternInsights($dashboardMetrics['pattern_insights']);

            // Render alerts
            $components['alerts'] = $this->renderAlerts($dashboardMetrics['alerts_summary']);

            // Render trend analysis
            $components['trend_analysis'] = $this->renderTrendAnalysis($dashboardMetrics['trend_analysis']);

            // Store visualizations
            $this->storeDashboardVisualizations($dashboard, $components);

            Log::info('Dashboard components rendered successfully', [
                'dashboard_id' => $dashboard->id,
                'components_count' => count($components)
            ]);

            return $components;

        } catch (\Exception $e) {
            Log::error('Failed to render dashboard components', [
                'dashboard_id' => $dashboard->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 4.4: Render Dashboard Components (Alerts)
     */
    public function renderAlerts(array $alertsSummary): array
    {
        try {
            $renderedAlerts = [];

            foreach ($alertsSummary as $alert) {
                $renderedAlerts[] = [
                    'id' => $alert['id'] ?? null,
                    'title' => $alert['title'] ?? 'Unknown Alert',
                    'description' => $alert['description'] ?? '',
                    'severity' => $alert['severity'] ?? 'medium',
                    'status' => $alert['status'] ?? 'open',
                    'triggered_at' => $alert['triggered_at'] ?? now()->toISOString(),
                    'icon' => $this->getAlertIcon($alert['severity'] ?? 'medium'),
                    'color' => $this->getAlertColor($alert['severity'] ?? 'medium'),
                    'action_required' => $this->determineActionRequired($alert),
                ];
            }

            Log::info('Alerts rendered successfully', [
                'alerts_count' => count($renderedAlerts)
            ]);

            return $renderedAlerts;

        } catch (\Exception $e) {
            Log::error('Failed to render alerts', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Determine metric status based on value and threshold
     */
    private function determineMetricStatus(array $metric): string
    {
        $value = $metric['value'] ?? 0;
        $threshold = $metric['threshold'] ?? null;

        if ($threshold === null) {
            return 'normal';
        }

        if ($value > $threshold) {
            return 'critical';
        } elseif ($value > ($threshold * 0.8)) {
            return 'warning';
        }

        return 'normal';
    }

    /**
     * Format metric value with unit
     */
    private function formatValue(float $value, string $unit): string
    {
        switch ($unit) {
            case '%':
                return round($value, 2) . '%';
            case 'ms':
                return round($value, 2) . 'ms';
            case 'GB':
                return round($value, 2) . 'GB';
            case 'MB':
                return round($value, 2) . 'MB';
            default:
                return (string) round($value, 2);
        }
    }

    /**
     * Process pattern insights for dashboard
     */
    private function processPatternInsights(array $patterns): array
    {
        $insights = [];

        foreach ($patterns as $pattern) {
            $insights[] = [
                'pattern_type' => $pattern['type'] ?? 'unknown',
                'description' => $pattern['description'] ?? '',
                'confidence' => $pattern['confidence'] ?? 0,
                'impact' => $pattern['impact'] ?? 'medium',
                'recommendation' => $pattern['recommendation'] ?? '',
                'detected_at' => $pattern['detected_at'] ?? now()->toISOString(),
            ];
        }

        return $insights;
    }

    /**
     * Generate health indicators
     */
    private function generateHealthIndicators(array $metrics): array
    {
        $healthIndicators = [
            'overall_health' => $this->calculateOverallHealth($metrics),
            'system_health' => $this->calculateSystemHealth($metrics),
            'api_health' => $this->calculateApiHealth($metrics),
            'performance_health' => $this->calculatePerformanceHealth($metrics),
        ];

        return $healthIndicators;
    }

    /**
     * Generate alerts summary
     */
    private function generateAlertsSummary(array $metrics): array
    {
        $alerts = [];

        foreach ($metrics as $metric) {
            if ($metric['status'] === 'critical' || $metric['status'] === 'warning') {
                $alerts[] = [
                    'id' => $metric['id'] ?? null,
                    'title' => $metric['name'] . ' Alert',
                    'description' => $metric['name'] . ' is ' . $metric['status'],
                    'severity' => $metric['status'] === 'critical' ? 'high' : 'medium',
                    'status' => 'open',
                    'triggered_at' => $metric['timestamp'] ?? now()->toISOString(),
                    'metric_id' => $metric['id'] ?? null,
                ];
            }
        }

        return $alerts;
    }

    /**
     * Analyze trends in metrics
     */
    private function analyzeTrends(array $metrics): array
    {
        $trends = [];

        foreach ($metrics as $metric) {
            $trends[] = [
                'metric_name' => $metric['name'],
                'trend' => $metric['trend'] ?? 'stable',
                'change_percentage' => $this->calculateChangePercentage($metric),
                'period' => '24h',
                'direction' => $this->getTrendDirection($metric['trend'] ?? 'stable'),
            ];
        }

        return $trends;
    }

    /**
     * Render performance charts
     */
    private function renderPerformanceCharts(array $metrics): array
    {
        $charts = [];

        // CPU usage chart
        $charts['cpu_usage'] = [
            'type' => 'line',
            'title' => 'CPU Usage',
            'data' => $this->extractChartData($metrics, 'cpu_usage'),
            'config' => [
                'yAxis' => ['title' => ['text' => 'CPU %']],
                'xAxis' => ['type' => 'datetime'],
            ],
        ];

        // Memory usage chart
        $charts['memory_usage'] = [
            'type' => 'area',
            'title' => 'Memory Usage',
            'data' => $this->extractChartData($metrics, 'memory_usage'),
            'config' => [
                'yAxis' => ['title' => ['text' => 'Memory %']],
                'xAxis' => ['type' => 'datetime'],
            ],
        ];

        // API response time chart
        $charts['api_response_time'] = [
            'type' => 'bar',
            'title' => 'API Response Times',
            'data' => $this->extractChartData($metrics, 'response_time'),
            'config' => [
                'yAxis' => ['title' => ['text' => 'Response Time (ms)']],
                'xAxis' => ['type' => 'category'],
            ],
        ];

        return $charts;
    }

    /**
     * Render health indicators
     */
    private function renderHealthIndicators(array $healthIndicators): array
    {
        $rendered = [];

        foreach ($healthIndicators as $key => $value) {
            $rendered[$key] = [
                'label' => $this->formatHealthLabel($key),
                'value' => $value,
                'status' => $this->getHealthStatus($value),
                'color' => $this->getHealthColor($value),
                'icon' => $this->getHealthIcon($value),
            ];
        }

        return $rendered;
    }

    /**
     * Render pattern insights
     */
    private function renderPatternInsights(array $insights): array
    {
        $rendered = [];

        foreach ($insights as $insight) {
            $rendered[] = [
                'title' => $insight['pattern_type'],
                'description' => $insight['description'],
                'confidence' => round($insight['confidence'], 2),
                'impact' => $insight['impact'],
                'recommendation' => $insight['recommendation'],
                'detected_at' => $insight['detected_at'],
                'badge_color' => $this->getImpactColor($insight['impact']),
            ];
        }

        return $rendered;
    }

    /**
     * Render trend analysis
     */
    private function renderTrendAnalysis(array $trends): array
    {
        $rendered = [];

        foreach ($trends as $trend) {
            $rendered[] = [
                'metric' => $trend['metric_name'],
                'trend' => $trend['trend'],
                'direction' => $trend['direction'],
                'change_percentage' => round($trend['change_percentage'], 2),
                'period' => $trend['period'],
                'icon' => $this->getTrendIcon($trend['direction']),
                'color' => $this->getTrendColor($trend['direction']),
            ];
        }

        return $rendered;
    }

    /**
     * Store dashboard visualizations
     */
    private function storeDashboardVisualizations(Dashboard $dashboard, array $components): void
    {
        foreach ($components as $componentName => $componentData) {
            DashboardVisualization::create([
                'dashboard_id' => $dashboard->id,
                'component_name' => $componentName,
                'visualization_type' => $this->getVisualizationType($componentName),
                'visualization_data' => $componentData,
                'render_config' => $this->getRenderConfig($componentName),
                'rendered_at' => now(),
            ]);
        }
    }

    // Helper methods for rendering and formatting
    private function getAlertIcon(string $severity): string
    {
        return match($severity) {
            'critical' => 'exclamation-triangle',
            'high' => 'exclamation-circle',
            'medium' => 'exclamation',
            'low' => 'info-circle',
            default => 'bell',
        };
    }

    private function getAlertColor(string $severity): string
    {
        return match($severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }

    private function determineActionRequired(array $alert): bool
    {
        return in_array($alert['severity'] ?? 'medium', ['critical', 'high']);
    }

    private function calculateOverallHealth(array $metrics): float
    {
        $totalHealth = 0;
        $count = 0;

        foreach ($metrics as $metric) {
            $health = match($metric['status']) {
                'normal' => 100,
                'warning' => 70,
                'critical' => 30,
                default => 50,
            };
            $totalHealth += $health;
            $count++;
        }

        return $count > 0 ? round($totalHealth / $count, 2) : 100;
    }

    private function calculateSystemHealth(array $metrics): float
    {
        // Implementation for system-specific health calculation
        return $this->calculateOverallHealth($metrics);
    }

    private function calculateApiHealth(array $metrics): float
    {
        // Implementation for API-specific health calculation
        return $this->calculateOverallHealth($metrics);
    }

    private function calculatePerformanceHealth(array $metrics): float
    {
        // Implementation for performance-specific health calculation
        return $this->calculateOverallHealth($metrics);
    }

    private function calculateChangePercentage(array $metric): float
    {
        // Simplified calculation - would need historical data
        return 0.0;
    }

    private function getTrendDirection(string $trend): string
    {
        return match($trend) {
            'up' => 'increasing',
            'down' => 'decreasing',
            default => 'stable',
        };
    }

    private function extractChartData(array $metrics, string $metricType): array
    {
        // Simplified data extraction - would need proper time-series data
        return [
            'categories' => ['Now'],
            'series' => [
                [
                    'name' => $metricType,
                    'data' => [0],
                ]
            ]
        ];
    }

    private function formatHealthLabel(string $key): string
    {
        return match($key) {
            'overall_health' => 'Overall Health',
            'system_health' => 'System Health',
            'api_health' => 'API Health',
            'performance_health' => 'Performance Health',
            default => ucfirst(str_replace('_', ' ', $key)),
        };
    }

    private function getHealthStatus(float $value): string
    {
        return match(true) {
            $value >= 80 => 'excellent',
            $value >= 60 => 'good',
            $value >= 40 => 'fair',
            $value >= 20 => 'poor',
            default => 'critical',
        };
    }

    private function getHealthColor(float $value): string
    {
        return match(true) {
            $value >= 80 => 'green',
            $value >= 60 => 'blue',
            $value >= 40 => 'yellow',
            $value >= 20 => 'orange',
            default => 'red',
        };
    }

    private function getHealthIcon(float $value): string
    {
        return match(true) {
            $value >= 80 => 'check-circle',
            $value >= 60 => 'thumbs-up',
            $value >= 40 => 'minus-circle',
            $value >= 20 => 'exclamation-triangle',
            default => 'times-circle',
        };
    }

    private function getImpactColor(string $impact): string
    {
        return match($impact) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    private function getTrendIcon(string $direction): string
    {
        return match($direction) {
            'increasing' => 'arrow-up',
            'decreasing' => 'arrow-down',
            default => 'arrow-right',
        };
    }

    private function getTrendColor(string $direction): string
    {
        return match($direction) {
            'increasing' => 'green',
            'decreasing' => 'red',
            default => 'gray',
        };
    }

    private function getVisualizationType(string $componentName): string
    {
        return match($componentName) {
            'performance_charts' => 'chart',
            'health_indicators' => 'metric',
            'pattern_insights' => 'insight',
            'alerts' => 'alert',
            'trend_analysis' => 'trend',
            default => 'component',
        };
    }

    private function getRenderConfig(string $componentName): array
    {
        return [
            'refresh_interval' => 30, // seconds
            'auto_update' => true,
            'layout' => $this->getComponentLayout($componentName),
        ];
    }

    private function getComponentLayout(string $componentName): array
    {
        return match($componentName) {
            'performance_charts' => ['width' => 'full', 'height' => 'medium'],
            'health_indicators' => ['width' => 'half', 'height' => 'small'],
            'pattern_insights' => ['width' => 'half', 'height' => 'medium'],
            'alerts' => ['width' => 'full', 'height' => 'small'],
            'trend_analysis' => ['width' => 'full', 'height' => 'small'],
            default => ['width' => 'full', 'height' => 'medium'],
        };
    }
}
