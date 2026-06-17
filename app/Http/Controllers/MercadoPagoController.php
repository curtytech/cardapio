<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\MercadoPagoInfo;
use App\Models\Payment;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class MercadoPagoController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role === 'admin') {
            abort(403, 'Apenas usuários não-admin podem iniciar pagamento.');
        }

        $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
        $webhookUrl = env('MERCADOPAGO_WEBHOOK_URL');
        $precoAnual = (float) env('PRECO_ANUAL', 60);
        $sandbox = filter_var(env('MERCADOPAGO_SANDBOX', false), FILTER_VALIDATE_BOOLEAN);

        // Forçar URLs públicas em https
        $ensureHttps = function (string $url): string {
            if (str_starts_with($url, 'http://')) {
                return 'https://' . substr($url, 7);
            }
            return $url;
        };

        $baseUrl = rtrim(config('app.url'), '/');
        $successUrl = env('MERCADOPAGO_SUCCESS_URL', $baseUrl . '/dashboard');
        $failureUrl = env('MERCADOPAGO_FAILURE_URL', $baseUrl . '/dashboard');
        $pendingUrl = env('MERCADOPAGO_PENDING_URL', $baseUrl . '/dashboard');

        $successUrl = $ensureHttps($successUrl);
        $failureUrl = $ensureHttps($failureUrl);
        $pendingUrl = $ensureHttps($pendingUrl);
        $webhookUrl = $ensureHttps($webhookUrl);

        $payload = [
            'items' => [[
                'title' => 'Assinatura Anual',
                'quantity' => 1,
                'currency_id' => 'BRL',
                'unit_price' => $precoAnual,
            ]],
            'payer' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'back_urls' => [
                'success' => $successUrl,
                'failure' => $failureUrl,
                'pending' => $pendingUrl,
            ],
            'auto_return' => 'approved',
            'notification_url' => $webhookUrl,
            'external_reference' => (string) $user->id,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ];

        $response = Http::withToken($accessToken)->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if (!$response->successful()) {
            Log::error('Erro ao criar preferência Mercado Pago', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);
            return back()->with('error', 'Erro na API do Mercado Pago: ' . $response->status());
        }

        $pref = $response->json();
        $redirectUrl = $sandbox ? ($pref['sandbox_init_point'] ?? $pref['init_point'] ?? null) : ($pref['init_point'] ?? null);
        if (!$redirectUrl) {
            return back()->with('error', 'Preferência criada, mas não há URL de checkout.');
        }

        return redirect()->away($redirectUrl);
    }

    public function webhook(Request $request)
    {
        Log::info('Webhook Mercado Pago recebido', [
            'query' => $request->query(),
            'body' => $request->all(),
            'headers' => [
                'Content-Type' => $request->header('Content-Type'),
                'X-Request-Id' => $request->header('X-Request-Id'),
            ],
        ]);

        $context = (string) ($request->query('context') ?? $request->input('context') ?? 'subscription');
        $userIdFromQuery = (int) ($request->query('user_id') ?? $request->input('user_id') ?? 0);
        $accessToken = $this->resolveAccessToken($context, $userIdFromQuery);

        if (!$accessToken) {
            Log::error('Access token do Mercado Pago nao configurado para o webhook', [
                'context' => $context,
                'user_id' => $userIdFromQuery,
            ]);
            return response()->json(['ok' => true], 200);
        }

        $paymentId = $request->input('data.id')
            ?? $request->input('id')
            ?? $request->query('id');

        $eventType = $request->input('type') ?? $request->query('type') ?? 'payment';
        $action = $request->input('action') ?? null;

        if (!$paymentId) {
            Log::warning('Webhook sem paymentId', ['payload' => $request->all()]);
            return response()->json(['ok' => true], 200);
        }

        if ($eventType !== 'payment') {
            Log::info('Evento ignorado (não é payment)', ['type' => $eventType, 'action' => $action]);
            return response()->json(['ok' => true], 200);
        }

        $paymentResp = Http::withToken($accessToken)
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (!$paymentResp->successful()) {
            Log::error('Falha ao consultar pagamento no Mercado Pago', [
                'payment_id' => $paymentId,
                'status' => $paymentResp->status(),
                'body' => $paymentResp->body(),
            ]);
            return response()->json(['ok' => true], 200);
        }

        $detail = $paymentResp->json();
        Log::info('Detalhe do pagamento obtido', [
            'payment_id' => $paymentId,
            'status' => data_get($detail, 'status'),
            'external_reference' => data_get($detail, 'external_reference'),
        ]);

        $status = data_get($detail, 'status');
        $dateApproved = data_get($detail, 'date_approved'); // ISO8601
        $userId = (int) (data_get($detail, 'metadata.user_id') ?? data_get($detail, 'external_reference') ?? 0);
        $paymentContext = (string) (data_get($detail, 'metadata.payment_context') ?? $context ?: 'subscription');
        $deliveryId = data_get($detail, 'metadata.delivery_id');
        $sellId = data_get($detail, 'metadata.sell_id');
        $amount = (float) (data_get($detail, 'transaction_amount') ?? 0);

        $dataPagamento = $dateApproved ? Carbon::parse($dateApproved) : null;
        $expiration = $paymentContext === 'subscription' && $dataPagamento ? $dataPagamento->copy()->addYear() : null;

        // Tentativas para preference_id
        $preferenceId =
            data_get($detail, 'metadata.preference_id')
            ?? data_get($detail, 'order.id')
            ?? null;

        Payment::updateOrCreate(
            ['mercadopago_payment_id' => (string) $paymentId],
            [
                'user_id' => $userId ?: null,
                'payment_context' => 'subscription',
                'mercadopago_preference_id' => $preferenceId,
                'mercadopago_status' => $status,
                'data_pagamento' => $dataPagamento,
                'mercadopago_preference_id' => $preferenceId,
                'mercadopago_status' => $status,
                'data_pagamento' => $dataPagamento,
                'expiration_date' => $expiration,
                'mercadopago_response' => json_encode($detail),
                'amount' => $amount ?: null,
            ]
        );

        if ($paymentContext === 'delivery' && $status === 'approved') {
            Delivery::query()
                ->whereKey($deliveryId)
                ->update([
                    'is_paid' => true,
                    'payment_method' => 'mercado_pago',
                ]);

            Sell::query()
                ->whereKey($sellId)
                ->update([
                    'is_paid' => true,
                ]);
        }

        Log::info('Pagamento registrado/atualizado com sucesso', [
            'payment_id' => $paymentId,
            'user_id' => $userId ?: null,
            'status' => $status,
        ]);

        return response()->json(['ok' => true], 200);
    }

    protected function resolveAccessToken(string $context, int $userId): ?string
    {
        if ($context === 'delivery' && $userId > 0) {
            return MercadoPagoInfo::query()
                ->where('user_id', $userId)
                ->value('mercadopago_access_token');
        }

        return env('MERCADOPAGO_ACCESS_TOKEN');
    }
}
