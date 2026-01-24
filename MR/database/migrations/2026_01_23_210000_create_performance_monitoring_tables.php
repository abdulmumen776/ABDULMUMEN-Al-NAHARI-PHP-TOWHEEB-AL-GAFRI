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
        Schema::create('clients', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('name');
            $table->string('industry')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('operations', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('operation_id')->constrained()->cascadeOnDelete();
            $table->string('metric_name');
            $table->string('metric_type');
            $table->decimal('value', 10, 2);
            $table->string('unit')->nullable();
            $table->decimal('threshold', 10, 2)->nullable();
            $table->string('status')->default('normal');
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dashboards', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('visibility')->default('internal');
            $table->timestamps();
        });

        Schema::create('dashboard_performance_metric', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('dashboard_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_metric_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
            $table->unique(['dashboard_id', 'performance_metric_id'], 'dashboard_metric_unique');
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('performance_metric_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('severity')->default('medium');
            $table->string('status')->default('open');
            $table->timestamp('triggered_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('administrators', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('role')->default('analyst');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('administrator_metric_reviews', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('administrator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_metric_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['administrator_id', 'performance_metric_id'], 'administrator_metric_unique');
        });

        Schema::create('administrator_alert_reviews', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('administrator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alert_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->unique(['administrator_id', 'alert_id'], 'administrator_alert_unique');
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('performance_metric_id')->constrained()->cascadeOnDelete();
            $table->text('recommendation_text');
            $table->string('status')->default('pending');
            $table->timestamp('implemented_at')->nullable();
            $table->timestamps();
        });

        Schema::create('apis', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('base_url');
            $table->string('owner')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('monitored');
            $table->timestamps();
        });

        Schema::create('api_performance_logs', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('api_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_metric_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('status_code')->nullable();
            $table->decimal('response_time_ms', 10, 2)->nullable();
            $table->decimal('payload_size_kb', 10, 2)->nullable();
            $table->decimal('error_rate', 5, 2)->nullable();
            $table->timestamp('monitored_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_performance_logs');
        Schema::dropIfExists('apis');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('administrator_alert_reviews');
        Schema::dropIfExists('administrator_metric_reviews');
        Schema::dropIfExists('administrators');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('dashboard_performance_metric');
        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('operations');
        Schema::dropIfExists('clients');
    }
};
