<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'action_type',
        'raw_data',
        'status',
        'received_at',
        'processed_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(ActionMetadata::class);
    }

    public function enrichedAction(): HasMany
    {
        return $this->hasMany(EnrichedAction::class);
    }

    public function acknowledgments(): HasMany
    {
        return $this->hasMany(ActionAcknowledgment::class);
    }
}
