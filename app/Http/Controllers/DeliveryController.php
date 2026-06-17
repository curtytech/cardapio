<?php

namespace App\Http\Controllers;

use App\Actions\CreateDeliveryOrderAction;
use App\Actions\CreateDeliveryMercadoPagoCheckoutAction;
use App\Http\Requests\StoreDeliveryOrderRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DeliveryController extends Controller
{
    public function store(
        StoreDeliveryOrderRequest $request,
        CreateDeliveryOrderAction $action,
        CreateDeliveryMercadoPagoCheckoutAction $checkoutAction
    ): JsonResponse
    {
        try {
            $user = User::query()->findOrFail((int) $request->validated('user_id'));
            $delivery = $action->execute($user, $request->validated());
            $checkout = $checkoutAction->execute($delivery);

            return response()->json([
                'success' => true,
                'message' => 'Pedido criado. Redirecionando para pagamento...',
                'delivery_id' => $delivery->id,
                'sell_id' => $delivery->sell_id,
                'url' => $checkout['url'],
                'preference_id' => $checkout['preference_id'],
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Throwable $exception) {
            Log::error('Erro ao processar delivery', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'error' => 'Erro ao processar pedido de delivery.',
            ], 500);
        }
    }
}
