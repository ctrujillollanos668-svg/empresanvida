<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'transaction_hash',
        'proof_image',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProofUrlAttribute(): ?string
    {
        if (!$this->proof_image) {
            return null;
        }

        if (str_starts_with($this->proof_image, 'http://') || str_starts_with($this->proof_image, 'https://')) {
            return $this->proof_image;
        }

        if (str_starts_with($this->proof_image, 'uploads/')) {
            return asset($this->proof_image);
        }

        return asset('storage/' . $this->proof_image);
    }
}
