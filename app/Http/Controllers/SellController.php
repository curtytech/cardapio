<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    public function clientBuys(Request $request)
    {
        // Validação básica
       $request->validate([
            'cart' => 'required|array|min:1',
            'total' => 'required|numeric',
            'table_id' => 'required|exists:restaurant_tables,id',
            'client_name' => 'required|string|max:255',
            'observation' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // dump($request->all());
        // die;

        try {
            DB::beginTransaction();

            $cart = $request->input('cart');
            $total = $request->input('total');
            $tableId = $request->input('table_id');
            $clientName = $request->input('client_name');
            $observation = $request->input('observation');
            $userId = (int) $request->input('user_id');
            // Cria a venda
            $sell = \App\Models\Sell::create([
                'user_id' => $userId, 
                'table_id' => $tableId,
                'client_name' => $clientName,
                'date' => now(),
                'observation' => $observation,
                'is_paid' => false,
                'is_finished' => false,
                'ip' => $request->ip(),
                'total' => $total
            ]);

            // Salva os itens do carrinho
            foreach ($cart as $item) {
                \App\Models\SellProductGroup::create([
                    'sell_id' => $sell->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();

            // Retorna URL de redirecionamento ou sucesso
            // Se tiver integração com pagamento, aqui geraria a preference

            return response()->json([
                'success' => true,
                'message' => 'Pedido realizado com sucesso!',
                'sell_id' => $sell->id,
                // 'url' => route('payment.checkout', $sell->id) // Exemplo se tiver pagamento
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar venda: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar pedido: ' . $e->getMessage()], 500);
        }
    }

    public function clientOrders(Request $request)
    {
        $request->validate([
            'sell_ids' => 'required|array',
            'sell_ids.*' => 'integer|exists:sells,id',
            'table_id' => 'required|exists:restaurant_tables,id',
        ]);

        $sellIds = $request->input('sell_ids');

        $sells = \App\Models\Sell::whereIn('id', $sellIds)
            ->where('table_id', $request->input('table_id'))
            ->with(['sellProductsGroups.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $sells
        ]);
    }
}
