<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiPerformanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'performance_metric_id',
        'status_code',
        'response_time_ms',
        'payload_size_kb',
        'error_rate',
        'monitored_at',
    ];

    protected $casts = [
        'monitored_at' => 'datetime',
    ];

    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    public function performanceMetric(): BelongsTo
    {
        return $this->belongsTo(PerformanceMetric::class);
    }
}
