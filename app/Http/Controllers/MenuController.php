<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\UserPageView;
use Illuminate\Support\Carbon;

class MenuController extends Controller
{
    public function show(string $slug, ?string $tableNumber = null)
    {
        return $this->renderMenu($slug, $tableNumber, false);
    }

    public function delivery(string $slug)
    {
        return $this->renderMenu($slug, null, true);
    }

    protected function renderMenu(string $slug, ?string $tableNumber = null, bool $isDelivery = false)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $hasPayment = Payment::where('user_id', $user->id)
            ->where('mercadopago_status', 'approved')
            ->where(function ($q) {
                $q->whereNull('expiration_date')->orWhere('expiration_date', '>', now());
            })
            ->exists();

        // Contador de acessos com proteção por sessão (anti-refresh 5 min)
        $sessionKey = 'viewed_user_' . $user->id;
        $now = Carbon::now();
        $last = session($sessionKey) ? Carbon::parse(session($sessionKey)) : null;
        $shouldCount = !$last || $last->diffInMinutes($now) >= 5;

        if ($shouldCount) {
            $view = UserPageView::firstOrCreate(
                ['user_id' => $user->id],
                ['slug' => $user->slug, 'views_count' => 0]
            );
            $view->increment('views_count');
            $view->last_viewed_at = $now;
            $view->save();

            session([$sessionKey => $now->toISOString()]);
        }

        $viewsTotal = UserPageView::where('user_id', $user->id)->value('views_count') ?? 0;

        $categories = Category::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('status', 'active')
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $categories = $categories->filter(function ($category) {
            return $category->products->count() > 0;
        });

        $restaurantTables = RestaurantTable::where('user_id', $user->id)
            ->orderBy('number')
            ->get();

        // Se o número da mesa foi informado mas não existe, redireciona para null
        if ($tableNumber && !$restaurantTables->contains('number', $tableNumber)) {
            return redirect()->route('menu.show', ['slug' => $slug]);
        }

        return view('menu.show', [
            'user' => $user,
            'categories' => $categories,
            'restaurantTables' => $restaurantTables,
            'tableNumber' => $tableNumber,
            'hasPayment' => $hasPayment,
            'viewsTotal' => $viewsTotal,
            'isDelivery' => $isDelivery,
        ]);
    }

    public function category(string $userSlug, string $categorySlug)
    {
        $user = User::where('slug', $userSlug)->firstOrFail();

        $category = Category::where('slug', $categorySlug)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('menu.category', compact('user', 'category', 'products'));
    }
}
