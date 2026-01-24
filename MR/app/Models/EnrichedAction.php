<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnrichedAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'client_id',
        'enriched_data',
        'validation_status',
        'enriched_at',
    ];

    protected $casts = [
        'enriched_data' => 'array',
        'enriched_at' => 'datetime',
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function validationResults(): HasMany
    {
        return $this->hasMany(ValidationResult::class);
    }
}
