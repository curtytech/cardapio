<?php

namespace App\Actions;

use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateDeliveryMercadoPagoCheckoutAction
{
    public function execute(Delivery $delivery): array
    {
        $delivery->loadMissing([
            'user.mercadoPagoInfo',
            'sell.sellProductsGroups.product',
        ]);

        $credentials = $delivery->user?->mercadoPagoInfo;

        if (! $credentials?->mercadopago_access_token || ! $credentials?->mercadopago_public_key) {
            throw ValidationException::withMessages([
                'mercadopago' => 'Esta loja ainda nao configurou as credenciais do Mercado Pago.',
            ]);
        }

        $baseUrl = rtrim(config('app.url'), '/');
        $deliveryUrl = route('menu.delivery', ['slug' => $delivery->user->slug]);
        $webhookBaseUrl = rtrim((string) env('MERCADOPAGO_WEBHOOK_URL', $baseUrl . '/api/webhook/mercadopago'), '/');
        $notificationUrl = $webhookBaseUrl . '?' . http_build_query([
            'user_id' => $delivery->user_id,
            'context' => 'delivery',
        ]);

        $payload = [
            'items' => $delivery->sell->sellProductsGroups->map(function ($group) {
                return [
                    'id' => (string) $group->product_id,
                    'title' => $group->product?->name ?? 'Produto',
                    'quantity' => (int) $group->quantity,
                    'currency_id' => 'BRL',
                    'unit_price' => (float) ($group->product?->sell_price ?? 0),
                ];
            })->values()->all(),
            'payer' => [
                'name' => $delivery->client_name,
                'email' => $delivery->user->email,
            ],
            'back_urls' => [
                'success' => $deliveryUrl . '?payment_status=success',
                'failure' => $deliveryUrl . '?payment_status=failure',
                'pending' => $deliveryUrl . '?payment_status=pending',
            ],
            'auto_return' => 'approved',
            'notification_url' => $notificationUrl,
            'external_reference' => 'delivery:' . $delivery->id,
            'metadata' => [
                'user_id' => $delivery->user_id,
                'delivery_id' => $delivery->id,
                'sell_id' => $delivery->sell_id,
                'payment_context' => 'delivery',
            ],
        ];

        $response = Http::withToken($credentials->mercadopago_access_token)
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if (! $response->successful()) {
            Log::error('Erro ao criar preferencia Mercado Pago para delivery', [
                'delivery_id' => $delivery->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw ValidationException::withMessages([
                'mercadopago' => 'Nao foi possivel iniciar o checkout do Mercado Pago.',
            ]);
        }

        $preference = $response->json();

        Payment::create([
            'user_id' => $delivery->user_id,
            'payment_context' => 'delivery',
            'sell_id' => $delivery->sell_id,
            'delivery_id' => $delivery->id,
            'mercadopago_preference_id' => $preference['id'] ?? null,
            'mercadopago_status' => 'pending',
            'mercadopago_response' => json_encode($preference),
            'amount' => $delivery->total,
        ]);

        return [
            'url' => $preference['init_point'] ?? null,
            'public_key' => $credentials->mercadopago_public_key,
            'preference_id' => $preference['id'] ?? null,
        ];
    }
}
