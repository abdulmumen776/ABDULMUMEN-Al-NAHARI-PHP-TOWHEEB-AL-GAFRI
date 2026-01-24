<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Api extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'base_url',
        'owner',
        'description',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function performanceLogs(): HasMany
    {
        return $this->hasMany(ApiPerformanceLog::class);
    }
}
