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
        Schema::create('actions', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('action_type');
            $table->json('raw_data');
            $table->string('status')->default('received');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('action_metadata', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('action_id')->constrained()->cascadeOnDelete();
            $table->json('metadata');
            $table->timestamp('extracted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('enriched_actions', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('action_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->json('enriched_data');
            $table->string('validation_status')->default('pending');
            $table->timestamp('enriched_at')->nullable();
            $table->timestamps();
        });

        Schema::create('validation_results', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('enriched_action_id')->constrained()->cascadeOnDelete();
            $table->string('validation_type'); // client_data, operands_data
            $table->boolean('is_valid')->default(false);
            $table->json('validation_errors')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('performance_datasets', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('dataset_name');
            $table->json('server_performance_data')->nullable();
            $table->json('api_performance_data')->nullable();
            $table->json('calculated_metrics')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pattern_analysis_results', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('analysis_type');
            $table->json('input_data');
            $table->json('identified_patterns');
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dashboard_visualizations', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('dashboard_id')->constrained()->cascadeOnDelete();
            $table->string('component_name');
            $table->string('visualization_type'); // chart, table, metric, alert
            $table->json('visualization_data');
            $table->json('render_config')->nullable();
            $table->timestamp('rendered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_visualizations');
        Schema::dropIfExists('pattern_analysis_results');
        Schema::dropIfExists('performance_datasets');
        Schema::dropIfExists('validation_results');
        Schema::dropIfExists('enriched_actions');
        Schema::dropIfExists('action_metadata');
        Schema::dropIfExists('actions');
    }
};
