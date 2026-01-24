<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'metric_name',
        'metric_type',
        'value',
        'unit',
        'threshold',
        'status',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function dashboards(): BelongsToMany
    {
        return $this->belongsToMany(Dashboard::class)
            ->withPivot(['display_order'])
            ->withTimestamps();
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function metricReviews(): HasMany
    {
        return $this->hasMany(AdministratorMetricReview::class);
    }

    public function apiPerformanceLogs(): HasMany
    {
        return $this->hasMany(ApiPerformanceLog::class);
    }
}
