<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'invested_amount',
        'daily_earning',
        'earned_so_far',
        'max_earning',
        'last_claimed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'invested_amount' => 'decimal:2',
            'daily_earning' => 'decimal:2',
            'earned_so_far' => 'decimal:2',
            'max_earning' => 'decimal:2',
            'last_claimed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Valida si ya transcurrieron exactamente 24 horas (86.400 segundos) desde el último reclamo o compra.
     */
    public function canClaim(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->last_claimed_at) {
            return true;
        }

        return now()->diffInSeconds($this->last_claimed_at->copy()->addHours(24), false) <= 0;
    }

    /**
     * Segundos restantes exactos para el próximo reclamo de 24 horas.
     */
    public function secondsUntilNextClaim(): int
    {
        if (!$this->last_claimed_at) {
            return 0;
        }

        $nextClaimTime = $this->last_claimed_at->copy()->addHours(24);
        $diff = now()->diffInSeconds($nextClaimTime, false);

        return max(0, $diff);
    }
}
