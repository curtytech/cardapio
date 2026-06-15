<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'user_id',
        'sell_id',
        'client_name',
        'client_phone',
        'zipcode',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'complement',
        'reference',
        'observation',
        'delivery_fee',
        'subtotal',
        'total',
        'payment_method',
        'is_paid',
        'status',
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'is_paid' => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sell(): BelongsTo
    {
        return $this->belongsTo(Sell::class);
    }
}
