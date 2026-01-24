<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'metadata',
        'extracted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'extracted_at' => 'datetime',
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
