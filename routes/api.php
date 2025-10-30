<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Log;

// Webhook do MercadoPago - SEM CSRF
Route::post('/webhook/mercadopago', [MercadoPagoController::class, 'webhook'])
    ->name('mercadopago.webhook');

// Rota GET de diagnóstico (opcional) para verificar 200 em acesso público
Route::get('/webhook/mercadopago', function () {
    Log::info('Ping GET no webhook Mercado Pago - API route acessível');
    return response()->json([
        'status' => 'ok',
        'route' => '/api/webhook/mercadopago',
        'method' => 'GET',
        'timestamp' => now(),
    ]);
})->name('mercadopago.webhook.get');

// Rota de teste para webhook - SEM CSRF
Route::post('/test-webhook', function () {
    Log::info('Teste de webhook chamado via POST - API ROUTE');
    return response()->json([
        'status' => 'webhook_accessible_api',
        'timestamp' => now(),
        'route_type' => 'API - SEM CSRF'
    ]);
})->name('test.webhook.api');
