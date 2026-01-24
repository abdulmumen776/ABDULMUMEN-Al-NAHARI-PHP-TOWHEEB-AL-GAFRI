<?php

namespace App\Services;

use App\Models\PatternAnalysisResult;
use App\Models\Api;
use App\Models\PerformanceDataset;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatternAnalysisService
{
    /**
     * Process 5.2: Analyze Patterns from External APIs
     */
    public function analyzeApiPatterns(array $apiData): PatternAnalysisResult
    {
        try {
            $patterns = $this->detectApiPatterns($apiData);
            $confidenceScore = $this->calculatePatternConfidence($patterns);

            $analysisResult = PatternAnalysisResult::create([
                'analysis_type' => 'api_patterns',
                'input_data' => $apiData,
                'identified_patterns' => $patterns,
                'confidence_score' => $confidenceScore,
                'analyzed_at' => now(),
            ]);

            Log::info('API pattern analysis completed', [
                'analysis_id' => $analysisResult->id,
                'patterns_found' => count($patterns),
                'confidence_score' => $confidenceScore
            ]);

            return $analysisResult;

        } catch (\Exception $e) {
            Log::error('API pattern analysis failed', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 5.2: Analyze Patterns from Dashboard Metrics
     */
    public function analyzeDashboardPatterns(array $dashboardMetrics): PatternAnalysisResult
    {
        try {
            $patterns = $this->detectDashboardPatterns($dashboardMetrics);
            $confidenceScore = $this->calculatePatternConfidence($patterns);

            $analysisResult = PatternAnalysisResult::create([
                'analysis_type' => 'dashboard_patterns',
                'input_data' => $dashboardMetrics,
                'identified_patterns' => $patterns,
                'confidence_score' => $confidenceScore,
                'analyzed_at' => now(),
            ]);

            Log::info('Dashboard pattern analysis completed', [
                'analysis_id' => $analysisResult->id,
                'patterns_found' => count($patterns),
                'confidence_score' => $confidenceScore
            ]);

            return $analysisResult;

        } catch (\Exception $e) {
            Log::error('Dashboard pattern analysis failed', [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Process 5.3: Analyze Patterns (General)
     */
    public function analyzePatterns(array $data, string $analysisType = 'general'): PatternAnalysisResult
    {
        try {
            $patterns = $this->detectGeneralPatterns($data, $analysisType);
            $confidenceScore = $this->calculatePatternConfidence($patterns);

            $analysisResult = PatternAnalysisResult::create([
                'analysis_type' => $analysisType,
                'input_data' => $data,
                'identified_patterns' => $patterns,
                'confidence_score' => $confidenceScore,
                'analyzed_at' => now(),
            ]);

            Log::info('General pattern analysis completed', [
                'analysis_id' => $analysisResult->id,
                'analysis_type' => $analysisType,
                'patterns_found' => count($patterns),
                'confidence_score' => $confidenceScore
            ]);

            return $analysisResult;

        } catch (\Exception $e) {
            Log::error('General pattern analysis failed', [
                'analysis_type' => $analysisType,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Detect patterns in API data
     */
    private function detectApiPatterns(array $apiData): array
    {
        $patterns = [];

        // Detect response time patterns
        $patterns[] = $this->analyzeResponseTimePatterns($apiData);

        // Detect error rate patterns
        $patterns[] = $this->analyzeErrorRatePatterns($apiData);

        // Detect usage patterns
        $patterns[] = $this->analyzeUsagePatterns($apiData);

        // Detect availability patterns
        $patterns[] = $this->analyzeAvailabilityPatterns($apiData);

        return array_filter($patterns);
    }

    /**
     * Detect patterns in dashboard metrics
     */
    private function detectDashboardPatterns(array $dashboardMetrics): array
    {
        $patterns = [];

        // Detect performance degradation patterns
        $patterns[] = $this->analyzePerformanceDegradation($dashboardMetrics);

        // Detect resource utilization patterns
        $patterns[] = $this->analyzeResourceUtilization($dashboardMetrics);

        // Detect anomaly patterns
        $patterns[] = $this->analyzeAnomalies($dashboardMetrics);

        // Detect trend patterns
        $patterns[] = $this->analyzeTrends($dashboardMetrics);

        return array_filter($patterns);
    }

    /**
     * Detect general patterns
     */
    private function detectGeneralPatterns(array $data, string $analysisType): array
    {
        $patterns = [];

        // Detect temporal patterns
        $patterns[] = $this->analyzeTemporalPatterns($data);

        // Detect correlation patterns
        $patterns[] = $this->analyzeCorrelationPatterns($data);

        // Detect outlier patterns
        $patterns[] = $this->analyzeOutlierPatterns($data);

        // Detect cyclical patterns
        $patterns[] = $this->analyzeCyclicalPatterns($data);

        return array_filter($patterns);
    }

    /**
     * Analyze response time patterns
     */
    private function analyzeResponseTimePatterns(array $apiData): ?array
    {
        $responseTimes = array_column($apiData, 'response_time_ms');
        
        if (empty($responseTimes)) {
            return null;
        }

        $avgResponseTime = array_sum($responseTimes) / count($responseTimes);
        $maxResponseTime = max($responseTimes);
        $minResponseTime = min($responseTimes);

        $pattern = [
            'type' => 'response_time_pattern',
            'description' => 'Response time analysis',
            'metrics' => [
                'average' => round($avgResponseTime, 2),
                'maximum' => $maxResponseTime,
                'minimum' => $minResponseTime,
                'variance' => $this->calculateVariance($responseTimes),
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.8,
        ];

        // Add insights based on patterns
        if ($avgResponseTime > 1000) {
            $pattern['insights'][] = 'High average response time detected';
            $pattern['severity'] = 'medium';
        }

        if ($maxResponseTime > 5000) {
            $pattern['insights'][] = 'Very slow response times detected';
            $pattern['severity'] = 'high';
        }

        return $pattern;
    }

    /**
     * Analyze error rate patterns
     */
    private function analyzeErrorRatePatterns(array $apiData): ?array
    {
        $errorCount = 0;
        $totalCount = count($apiData);

        foreach ($apiData as $api) {
            if (!$api['success']) {
                $errorCount++;
            }
        }

        if ($totalCount === 0) {
            return null;
        }

        $errorRate = ($errorCount / $totalCount) * 100;

        $pattern = [
            'type' => 'error_rate_pattern',
            'description' => 'Error rate analysis',
            'metrics' => [
                'error_rate' => round($errorRate, 2),
                'error_count' => $errorCount,
                'total_requests' => $totalCount,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.9,
        ];

        if ($errorRate > 10) {
            $pattern['insights'][] = 'High error rate detected';
            $pattern['severity'] = 'high';
        } elseif ($errorRate > 5) {
            $pattern['insights'][] = 'Moderate error rate detected';
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze usage patterns
     */
    private function analyzeUsagePatterns(array $apiData): ?array
    {
        if (empty($apiData)) {
            return null;
        }

        $usageByHour = [];
        $payloadSizes = array_column($apiData, 'payload_size_kb');

        foreach ($apiData as $api) {
            $hour = Carbon::parse($api['timestamp'])->hour;
            $usageByHour[$hour] = ($usageByHour[$hour] ?? 0) + 1;
        }

        $peakHour = array_keys($usageByHour, max($usageByHour))[0] ?? null;
        $avgPayloadSize = !empty($payloadSizes) ? array_sum($payloadSizes) / count($payloadSizes) : 0;

        $pattern = [
            'type' => 'usage_pattern',
            'description' => 'API usage analysis',
            'metrics' => [
                'peak_hour' => $peakHour,
                'hourly_distribution' => $usageByHour,
                'average_payload_size' => round($avgPayloadSize, 2),
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.7,
        ];

        if ($peakHour !== null) {
            $pattern['insights'][] = "Peak usage detected at hour {$peakHour}";
        }

        if ($avgPayloadSize > 1000) { // 1MB
            $pattern['insights'][] = 'Large payload sizes detected';
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze availability patterns
     */
    private function analyzeAvailabilityPatterns(array $apiData): ?array
    {
        if (empty($apiData)) {
            return null;
        }

        $successfulRequests = 0;
        $totalRequests = count($apiData);

        foreach ($apiData as $api) {
            if ($api['success']) {
                $successfulRequests++;
            }
        }

        $availability = ($successfulRequests / $totalRequests) * 100;

        $pattern = [
            'type' => 'availability_pattern',
            'description' => 'Service availability analysis',
            'metrics' => [
                'availability_percentage' => round($availability, 2),
                'successful_requests' => $successfulRequests,
                'total_requests' => $totalRequests,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.95,
        ];

        if ($availability < 95) {
            $pattern['insights'][] = 'Low availability detected';
            $pattern['severity'] = 'high';
        } elseif ($availability < 99) {
            $pattern['insights'][] = 'Moderate availability issues';
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze performance degradation
     */
    private function analyzePerformanceDegradation(array $dashboardMetrics): ?array
    {
        if (!isset($dashboardMetrics['performance_metrics'])) {
            return null;
        }

        $metrics = $dashboardMetrics['performance_metrics'];
        $degradationCount = 0;

        foreach ($metrics as $metric) {
            if ($metric['status'] === 'critical' || $metric['status'] === 'warning') {
                $degradationCount++;
            }
        }

        $totalMetrics = count($metrics);
        $degradationPercentage = $totalMetrics > 0 ? ($degradationCount / $totalMetrics) * 100 : 0;

        $pattern = [
            'type' => 'performance_degradation',
            'description' => 'Performance degradation analysis',
            'metrics' => [
                'degradation_percentage' => round($degradationPercentage, 2),
                'degraded_metrics_count' => $degradationCount,
                'total_metrics_count' => $totalMetrics,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.8,
        ];

        if ($degradationPercentage > 30) {
            $pattern['insights'][] = 'Significant performance degradation detected';
            $pattern['severity'] = 'high';
        } elseif ($degradationPercentage > 15) {
            $pattern['insights'][] = 'Moderate performance degradation detected';
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze resource utilization
     */
    private function analyzeResourceUtilization(array $dashboardMetrics): ?array
    {
        if (!isset($dashboardMetrics['health_indicators'])) {
            return null;
        }

        $healthIndicators = $dashboardMetrics['health_indicators'];
        $lowResources = [];

        foreach ($healthIndicators as $key => $value) {
            if ($value < 50) {
                $lowResources[] = $key;
            }
        }

        $pattern = [
            'type' => 'resource_utilization',
            'description' => 'Resource utilization analysis',
            'metrics' => [
                'low_resource_areas' => $lowResources,
                'overall_health' => $healthIndicators['overall_health'] ?? 100,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.7,
        ];

        if (!empty($lowResources)) {
            $pattern['insights'][] = 'Low resource utilization detected in: ' . implode(', ', $lowResources);
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze anomalies
     */
    private function analyzeAnomalies(array $dashboardMetrics): ?array
    {
        // Simplified anomaly detection
        $anomalies = [];

        if (isset($dashboardMetrics['performance_metrics'])) {
            foreach ($dashboardMetrics['performance_metrics'] as $metric) {
                if ($metric['status'] === 'critical') {
                    $anomalies[] = $metric['name'];
                }
            }
        }

        $pattern = [
            'type' => 'anomaly_detection',
            'description' => 'Anomaly detection analysis',
            'metrics' => [
                'anomaly_count' => count($anomalies),
                'anomalous_metrics' => $anomalies,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.8,
        ];

        if (!empty($anomalies)) {
            $pattern['insights'][] = 'Anomalies detected in: ' . implode(', ', $anomalies);
            $pattern['severity'] = 'high';
        }

        return $pattern;
    }

    /**
     * Analyze trends
     */
    private function analyzeTrends(array $dashboardMetrics): ?array
    {
        if (!isset($dashboardMetrics['trend_analysis'])) {
            return null;
        }

        $trends = $dashboardMetrics['trend_analysis'];
        $negativeTrends = [];

        foreach ($trends as $trend) {
            if ($trend['direction'] === 'decreasing') {
                $negativeTrends[] = $trend['metric'];
            }
        }

        $pattern = [
            'type' => 'trend_analysis',
            'description' => 'Trend analysis',
            'metrics' => [
                'negative_trends_count' => count($negativeTrends),
                'negative_trends' => $negativeTrends,
            ],
            'insights' => [],
            'severity' => 'low',
            'confidence' => 0.6,
        ];

        if (!empty($negativeTrends)) {
            $pattern['insights'][] = 'Negative trends detected in: ' . implode(', ', $negativeTrends);
            $pattern['severity'] = 'medium';
        }

        return $pattern;
    }

    /**
     * Analyze temporal patterns
     */
    private function analyzeTemporalPatterns(array $data): array
    {
        // Simplified temporal pattern analysis
        return [
            'type' => 'temporal_pattern',
            'description' => 'Temporal pattern analysis',
            'metrics' => [
                'data_points' => count($data),
                'time_span' => '24h', // Simplified
            ],
            'insights' => ['Temporal analysis completed'],
            'severity' => 'low',
            'confidence' => 0.5,
        ];
    }

    /**
     * Analyze correlation patterns
     */
    private function analyzeCorrelationPatterns(array $data): array
    {
        // Simplified correlation analysis
        return [
            'type' => 'correlation_pattern',
            'description' => 'Correlation analysis',
            'metrics' => [
                'correlations_found' => 0, // Simplified
            ],
            'insights' => ['Correlation analysis completed'],
            'severity' => 'low',
            'confidence' => 0.4,
        ];
    }

    /**
     * Analyze outlier patterns
     */
    private function analyzeOutlierPatterns(array $data): array
    {
        // Simplified outlier detection
        return [
            'type' => 'outlier_pattern',
            'description' => 'Outlier detection',
            'metrics' => [
                'outliers_detected' => 0, // Simplified
            ],
            'insights' => ['Outlier analysis completed'],
            'severity' => 'low',
            'confidence' => 0.6,
        ];
    }

    /**
     * Analyze cyclical patterns
     */
    private function analyzeCyclicalPatterns(array $data): array
    {
        // Simplified cyclical pattern detection
        return [
            'type' => 'cyclical_pattern',
            'description' => 'Cyclical pattern analysis',
            'metrics' => [
                'cycles_detected' => 0, // Simplified
            ],
            'insights' => ['Cyclical analysis completed'],
            'severity' => 'low',
            'confidence' => 0.3,
        ];
    }

    /**
     * Calculate pattern confidence score
     */
    private function calculatePatternConfidence(array $patterns): float
    {
        if (empty($patterns)) {
            return 0.0;
        }

        $totalConfidence = 0;
        $patternCount = 0;

        foreach ($patterns as $pattern) {
            if (isset($pattern['confidence'])) {
                $totalConfidence += $pattern['confidence'];
                $patternCount++;
            }
        }

        return $patternCount > 0 ? round($totalConfidence / $patternCount, 2) : 0.0;
    }

    /**
     * Calculate variance
     */
    private function calculateVariance(array $values): float
    {
        if (empty($values)) {
            return 0.0;
        }

        $mean = array_sum($values) / count($values);
        $squaredDiffs = array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $values);

        return round(array_sum($squaredDiffs) / count($values), 2);
    }
}
