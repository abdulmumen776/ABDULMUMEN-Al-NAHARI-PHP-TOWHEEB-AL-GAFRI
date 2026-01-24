<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionAcknowledgment extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'acknowledgment_type',
        'acknowledgment_data',
        'sent_at',
    ];

    protected $casts = [
        'acknowledgment_data' => 'array',
        'sent_at' => 'datetime',
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
