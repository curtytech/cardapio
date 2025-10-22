<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Payment;

class MenuController extends Controller
{
    /**
     * Exibe o cardápio de um usuário específico baseado no slug.
     */
    public function show(string $slug)
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
        
        return view('menu.show', compact('user', 'categories', 'hasPayment'));
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
