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
        'payment_context',
        'sell_id',
        'delivery_id',
        'amount',
    ];

    protected $casts = [
        'data_pagamento' => 'datetime',
        'expiration_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sell(): BelongsTo
    {
        return $this->belongsTo(Sell::class);
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
