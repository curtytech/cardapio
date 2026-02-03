<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\UserPageView;
use Illuminate\Support\Carbon;

class MenuController extends Controller
{
    /**
     * Exibe o cardápio de um usuário específico baseado no slug.
     */
    public function show(string $slug, string $mesa)
    {
        // Busca o usuário pelo slug
        $user = User::where('slug', $slug)->firstOrFail();
        
        // Verifica se existe algum pagamento para esse usuário
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

        // Busca as categorias do usuário com seus produtos ativos
        $categories = Category::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('status', 'active')
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        
        // Filtra apenas categorias que têm produtos
        $categories = $categories->filter(function ($category) {
            return $category->products->count() > 0;
        });
        
        return view('menu.show', compact('user', 'categories', 'hasPayment', 'viewsTotal'));
    }
    
    /**
     * Exibe todos os produtos de uma categoria específica do usuário.
     */
    public function category(string $userSlug, string $categorySlug)
    {
        // Busca o usuário pelo slug
        $user = User::where('slug', $userSlug)->firstOrFail();
        
        // Busca a categoria pelo slug e usuário
        $category = Category::where('slug', $categorySlug)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Busca os produtos da categoria
        $products = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        return view('menu.category', compact('user', 'category', 'products'));
    }
}
