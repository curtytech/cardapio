<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MercadoPagoInfo extends Model
{
    protected $table = 'mercado_pago_infos';

    protected $fillable = [
        'user_id',
        'mercadopago_access_token',
        'mercadopago_public_key',
        'access_token',
    ];

    protected $casts = [
        'mercadopago_access_token' => 'encrypted',
        'mercadopago_public_key' => 'encrypted',
        'access_token' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
