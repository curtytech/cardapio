<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySale extends Model
{
    public $table = 'monthly_sales_view';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $casts = [
        'total' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}