<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mercadopago_payment_id',
        'mercadopago_preference_id',
        'mercadopago_status',
        'data_pagamento',
        'mercadopago_response',
        'expiration_date',
    ];

    protected $casts = [
        'data_pagamento' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
