<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'balance',
        'referral_code',
        'referred_by',
        'status',
        'last_spin_at',
        'roulette_spins',
        'claimed_red_packet',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_spin_at' => 'datetime',
            'roulette_spins' => 'integer',
            'claimed_red_packet' => 'boolean',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    public function canSpin(): bool
    {
        return ($this->roulette_spins ?? 0) > 0;
    }

    public function secondsUntilNextSpin(): int
    {
        if (!$this->last_spin_at) {
            return 0;
        }

        $nextSpinTime = $this->last_spin_at->copy()->addHours(24);
        $diff = now()->diffInSeconds($nextSpinTime, false);

        return max(0, $diff);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'cliente';
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function commissionsReceived(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'sponsor_id');
    }

    /**
     * Total dinero recargado y aprobado en el sistema.
     */
    public function totalDeposited(): float
    {
        return (float) $this->deposits()->where('status', 'approved')->sum('amount');
    }

    /**
     * Total dinero invertido en compras de planes VIP.
     */
    public function totalInvestedInPlans(): float
    {
        return (float) $this->userPlans()->sum('invested_amount');
    }

    /**
     * Saldo de recarga que aún no ha sido invertido en planes VIP.
     * Este saldo no es retirable directamente.
     */
    public function uninvestedDeposit(): float
    {
        return max(0, round($this->totalDeposited() - $this->totalInvestedInPlans(), 2));
    }

    /**
     * Saldo disponible exclusivamente para retiro (ganancias de planes,
     * comisiones por referidos, premios de ruleta y bonos).
     */
    public function withdrawableBalance(): float
    {
        $currentBalance = (float) $this->balance;
        $uninvested = $this->uninvestedDeposit();

        return max(0, round($currentBalance - $uninvested, 2));
    }
}
