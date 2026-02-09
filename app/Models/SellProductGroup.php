<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellProductGroup extends Model
{
    protected $table = 'sell_products_groups';

    public $timestamps = false;

    protected $fillable = [
        'sell_id',
        'product_id',
        'quantity',
    ];

    public function sell(): BelongsTo
    {
        return $this->belongsTo(Sell::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
