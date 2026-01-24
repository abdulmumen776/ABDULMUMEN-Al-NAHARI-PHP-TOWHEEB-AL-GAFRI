<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatternAnalysisResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'analysis_type',
        'input_data',
        'identified_patterns',
        'confidence_score',
        'analyzed_at',
    ];

    protected $casts = [
        'input_data' => 'array',
        'identified_patterns' => 'array',
        'confidence_score' => 'float',
        'analyzed_at' => 'datetime',
    ];
}
