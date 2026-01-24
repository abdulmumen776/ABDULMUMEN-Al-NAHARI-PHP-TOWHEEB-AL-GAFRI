<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'industry',
        'contact_email',
        'contact_phone',
        'status',
    ];

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }

    public function apis(): HasMany
    {
        return $this->hasMany(Api::class);
    }
}
