<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SellController extends Controller
{
    public function clientBuys(Request $request)
    {
        // Validação básica
        $request->validate([
            'cart' => 'required|array|min:1',
            'total' => 'required|numeric',
            'table_id' => 'required|exists:restaurant_tables,id',
            'observation' => 'nullable|string'
        ]);

        dump($request->all());
        // die;

        try {
            DB::beginTransaction();

            $cart = $request->input('cart');
            $total = $request->input('total');
            $tableId = $request->input('table_id');
            $observation = $request->input('observation');
            
            // Cria a venda
            $sell = \App\Models\Sell::create([
                'user_id' => auth()->id() ?? 1, // Fallback para user 1 se não autenticado (ajustar conforme lógica de negócio)
                'table_id' => $tableId,
                'client_name' => 'Cliente Mesa ' . $tableId, // Pode ser melhorado se tiver input de nome
                'date' => now(),
                'observation' => $observation,
                'is_paid' => false,
                'is_finished' => false,
                'total' => $total
            ]);

            // Salva os itens do carrinho
            foreach ($cart as $item) {
                \App\Models\ProductQuantity::create([
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
                // 'url' => route('payment.checkout', $sell->id) // Exemplo se tiver pagamento
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar venda: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar pedido: ' . $e->getMessage()], 500);
        }
    }

}