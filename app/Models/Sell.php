<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'is_finished' => 'boolean',
    ];
   protected $appends = ['mesa_label'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function sellProductsGroups(): HasMany
    {
        return $this->hasMany(SellProductGroup::class);
    }

    public function getMesaLabelAttribute(): ?string
{
    return $this->table?->number;
}
}
