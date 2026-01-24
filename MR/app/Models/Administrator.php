<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administrator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'role',
        'status',
    ];

    public function metricReviews(): HasMany
    {
        return $this->hasMany(AdministratorMetricReview::class);
    }

    public function alertReviews(): HasMany
    {
        return $this->hasMany(AdministratorAlertReview::class);
    }
}
