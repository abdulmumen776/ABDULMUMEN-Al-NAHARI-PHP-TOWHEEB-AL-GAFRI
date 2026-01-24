<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'enriched_action_id',
        'validation_type',
        'is_valid',
        'validation_errors',
        'validated_at',
    ];

    protected $casts = [
        'validation_errors' => 'array',
        'validated_at' => 'datetime',
    ];

    public function enrichedAction(): BelongsTo
    {
        return $this->belongsTo(EnrichedAction::class);
    }
}
