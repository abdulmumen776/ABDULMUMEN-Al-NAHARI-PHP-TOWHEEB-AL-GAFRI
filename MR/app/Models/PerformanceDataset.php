<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_name',
        'server_performance_data',
        'api_performance_data',
        'calculated_metrics',
        'generated_at',
    ];

    protected $casts = [
        'server_performance_data' => 'array',
        'api_performance_data' => 'array',
        'calculated_metrics' => 'array',
        'generated_at' => 'datetime',
    ];
}
