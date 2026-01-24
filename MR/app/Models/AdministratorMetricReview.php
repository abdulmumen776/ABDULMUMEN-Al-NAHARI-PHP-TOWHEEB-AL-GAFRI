<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministratorMetricReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrator_id',
        'performance_metric_id',
        'notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }

    public function performanceMetric(): BelongsTo
    {
        return $this->belongsTo(PerformanceMetric::class);
    }
}
