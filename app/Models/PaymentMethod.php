<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_holder',
        'account_type',
        'qr_image',
        'instructions',
        'color_theme',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
