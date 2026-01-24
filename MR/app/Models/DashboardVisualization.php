<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardVisualization extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_id',
        'component_name',
        'visualization_type',
        'visualization_data',
        'render_config',
        'rendered_at',
    ];

    protected $casts = [
        'visualization_data' => 'array',
        'render_config' => 'array',
        'rendered_at' => 'datetime',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }
}
