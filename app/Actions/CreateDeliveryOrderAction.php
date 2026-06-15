<?php

namespace App\Actions;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sell;
use App\Models\SellProductGroup;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateDeliveryOrderAction
{
    public function execute(User $user, array $payload): Delivery
    {
        $cart = collect($payload['cart'] ?? []);
        $products = $this->getProducts($user, $cart);
        $subtotal = $this->calculateSubtotal($cart, $products);
        $deliveryFee = 0.0;
        $total = $subtotal + $deliveryFee;

        return DB::transaction(function () use ($user, $payload, $cart, $products, $subtotal, $deliveryFee, $total) {
            $sell = Sell::create([
                'user_id' => $user->id,
                'table_id' => null,
                'client_name' => $payload['client_name'],
                'date' => now(),
                'observation' => $payload['observation'] ?? null,
                'is_paid' => false,
                'is_finished' => false,
                'ip' => request()->ip(),
                'total' => $subtotal,
                'status' => 'Pendente',
            ]);

            foreach ($cart as $item) {
                $product = $products->get((int) $item['id']);

                SellProductGroup::create([
                    'sell_id' => $sell->id,
                    'product_id' => $product->id,
                    'quantity' => (int) $item['quantity'],
                ]);
            }

            return Delivery::create([
                'user_id' => $user->id,
                'sell_id' => $sell->id,
                'client_name' => $payload['client_name'],
                'client_phone' => $payload['client_phone'] ?? null,
                'zipcode' => $payload['zipcode'] ?? null,
                'address' => $payload['address'],
                'number' => $payload['number'] ?? null,
                'neighborhood' => $payload['neighborhood'] ?? null,
                'city' => $payload['city'] ?? null,
                'state' => $payload['state'] ?? null,
                'complement' => $payload['complement'] ?? null,
                'reference' => $payload['reference'] ?? null,
                'observation' => $payload['observation'] ?? null,
                'delivery_fee' => $deliveryFee,
                'subtotal' => $subtotal,
                'total' => $total,
                'payment_method' => $payload['payment_method'] ?? null,
                'is_paid' => false,
                'status' => 'pendente',
            ]);
        });
    }

    protected function getProducts(User $user, Collection $cart): Collection
    {
        $productIds = $cart
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $products = Product::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            throw ValidationException::withMessages([
                'cart' => 'Um ou mais produtos do carrinho nao estao disponiveis.',
            ]);
        }

        return $products;
    }

    protected function calculateSubtotal(Collection $cart, Collection $products): float
    {
        return (float) $cart->sum(function (array $item) use ($products) {
            $product = $products->get((int) $item['id']);

            if (! $product) {
                return 0;
            }

            return ((int) $item['quantity']) * (float) $product->sell_price;
        });
    }
}
