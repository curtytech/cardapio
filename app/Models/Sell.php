<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sell extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'client_name',
        'date',
        'observation',
        'is_paid',
        'is_finished',
        'status',
        'ip',
        'total',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function getSellProductGroupAttribute(): string
    {
        $name = $this->product?->name ?? '';

        return $name . ' x ' . $this->quantity;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sellProductsGroups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SellProductGroup::class);
    }
}
