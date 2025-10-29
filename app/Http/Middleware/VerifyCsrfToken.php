<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // ... existing code ...
    protected $except = [
        'api/webhook/mercadopago',
    ];
    // ... existing code ...
}