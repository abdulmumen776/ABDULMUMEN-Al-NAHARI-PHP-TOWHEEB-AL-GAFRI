<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dashboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'visibility',
    ];

    public function performanceMetrics(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceMetric::class)
            ->withPivot(['display_order'])
            ->withTimestamps();
    }
}
