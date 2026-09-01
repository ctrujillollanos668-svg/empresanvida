<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'daily_percentage',
        'duration_days',
        'max_return',
        'badge',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'daily_percentage' => 'decimal:2',
            'max_return' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }
}
