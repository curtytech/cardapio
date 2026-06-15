<?php

namespace App\Http\Controllers;

use App\Actions\CreateDeliveryOrderAction;
use App\Http\Requests\StoreDeliveryOrderRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DeliveryController extends Controller
{
    public function store(StoreDeliveryOrderRequest $request, CreateDeliveryOrderAction $action): JsonResponse
    {
        try {
            $user = User::query()->findOrFail((int) $request->validated('user_id'));
            $delivery = $action->execute($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Pedido de delivery realizado com sucesso!',
                'delivery_id' => $delivery->id,
                'sell_id' => $delivery->sell_id,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
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
