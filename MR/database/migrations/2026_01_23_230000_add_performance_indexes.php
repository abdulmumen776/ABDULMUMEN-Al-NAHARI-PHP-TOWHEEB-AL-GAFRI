<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clients table indexes
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['name', 'status'], 'idx_clients_name_status');
            $table->index('industry', 'idx_clients_industry');
            $table->index('created_at', 'idx_clients_created_at');
            $table->index('updated_at', 'idx_clients_updated_at');
        });

        // Operations table indexes
        Schema::table('operations', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'idx_operations_client_status');
            $table->index('scheduled_at', 'idx_operations_scheduled_at');
            $table->index('type', 'idx_operations_type');
            $table->index('status', 'idx_operations_status');
            $table->index(['client_id', 'type'], 'idx_operations_client_type');
        });

        // Performance metrics table indexes
        Schema::table('performance_metrics', function (Blueprint $table) {
            $table->index(['operation_id', 'metric_name'], 'idx_metrics_operation_name');
            $table->index('recorded_at', 'idx_metrics_recorded_at');
            $table->index('status', 'idx_metrics_status');
            $table->index(['metric_type', 'status'], 'idx_metrics_type_status');
            $table->index(['operation_id', 'recorded_at'], 'idx_metrics_operation_time');
        });

        // APIs table indexes
        Schema::table('apis', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'idx_apis_client_status');
            $table->index('status', 'idx_apis_status');
            $table->index('base_url', 'idx_apis_base_url');
            $table->index('created_at', 'idx_apis_created_at');
        });

        // API performance logs table indexes
        Schema::table('api_performance_logs', function (Blueprint $table) {
            $table->index(['api_id', 'monitored_at'], 'idx_api_logs_api_time');
            $table->index('monitored_at', 'idx_api_logs_monitored_at');
            $table->index('status_code', 'idx_api_logs_status');
            $table->index('response_time_ms', 'idx_api_logs_response_time');
        });

        // Alerts table indexes
        Schema::table('alerts', function (Blueprint $table) {
            $table->index(['performance_metric_id', 'status'], 'idx_alerts_metric_status');
            $table->index('status', 'idx_alerts_status');
            $table->index('severity', 'idx_alerts_severity');
            $table->index('triggered_at', 'idx_alerts_triggered_at');
            $table->index(['severity', 'status'], 'idx_alerts_severity_status');
        });

        // Actions table indexes
        Schema::table('actions', function (Blueprint $table) {
            $table->index(['client_id', 'action_type'], 'idx_actions_client_type');
            $table->index('status', 'idx_actions_status');
            $table->index('received_at', 'idx_actions_received_at');
            $table->index('action_type', 'idx_actions_type');
        });

        // Enriched actions table indexes
        Schema::table('enriched_actions', function (Blueprint $table) {
            $table->index(['client_id', 'validation_status'], 'idx_enriched_client_validation');
            $table->index('validation_status', 'idx_enriched_validation_status');
            $table->index('enriched_at', 'idx_enriched_at');
        });

        // Pattern analysis results table indexes
        Schema::table('pattern_analysis_results', function (Blueprint $table) {
            $table->index('analysis_type', 'idx_pattern_analysis_type');
            $table->index('analyzed_at', 'idx_pattern_analyzed_at');
            $table->index('confidence_score', 'idx_pattern_confidence');
        });

        // Performance datasets table indexes
        Schema::table('performance_datasets', function (Blueprint $table) {
            $table->index('generated_at', 'idx_dataset_generated_at');
            $table->index('dataset_name', 'idx_dataset_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_name_status');
            $table->dropIndex('idx_clients_industry');
            $table->dropIndex('idx_clients_created_at');
            $table->dropIndex('idx_clients_updated_at');
        });

        Schema::table('operations', function (Blueprint $table) {
            $table->dropIndex('idx_operations_client_status');
            $table->dropIndex('idx_operations_scheduled_at');
            $table->dropIndex('idx_operations_type');
            $table->dropIndex('idx_operations_status');
            $table->dropIndex('idx_operations_client_type');
        });

        Schema::table('performance_metrics', function (Blueprint $table) {
            $table->dropIndex('idx_metrics_operation_name');
            $table->dropIndex('idx_metrics_recorded_at');
            $table->dropIndex('idx_metrics_status');
            $table->dropIndex('idx_metrics_type_status');
            $table->dropIndex('idx_metrics_operation_time');
        });

        Schema::table('apis', function (Blueprint $table) {
            $table->dropIndex('idx_apis_client_status');
            $table->dropIndex('idx_apis_status');
            $table->dropIndex('idx_apis_base_url');
            $table->dropIndex('idx_apis_created_at');
        });

        Schema::table('api_performance_logs', function (Blueprint $table) {
            $table->dropIndex('idx_api_logs_api_time');
            $table->dropIndex('idx_api_logs_monitored_at');
            $table->dropIndex('idx_api_logs_status');
            $table->dropIndex('idx_api_logs_response_time');
        });

        Schema::table('alerts', function (Blueprint $table) {
            $table->dropIndex('idx_alerts_metric_status');
            $table->dropIndex('idx_alerts_status');
            $table->dropIndex('idx_alerts_severity');
            $table->dropIndex('idx_alerts_triggered_at');
            $table->dropIndex('idx_alerts_severity_status');
        });

        Schema::table('actions', function (Blueprint $table) {
            $table->dropIndex('idx_actions_client_type');
            $table->dropIndex('idx_actions_status');
            $table->dropIndex('idx_actions_received_at');
            $table->dropIndex('idx_actions_type');
        });

        Schema::table('enriched_actions', function (Blueprint $table) {
            $table->dropIndex('idx_enriched_client_validation');
            $table->dropIndex('idx_enriched_validation_status');
            $table->dropIndex('idx_enriched_at');
        });

        Schema::table('pattern_analysis_results', function (Blueprint $table) {
            $table->dropIndex('idx_pattern_analysis_type');
            $table->dropIndex('idx_pattern_analyzed_at');
            $table->dropIndex('idx_pattern_confidence');
        });

        Schema::table('performance_datasets', function (Blueprint $table) {
            $table->dropIndex('idx_dataset_generated_at');
            $table->dropIndex('idx_dataset_name');
        });
    }
};
