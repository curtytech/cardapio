<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $categoriesCount = Category::count();
        $productsCount = Product::count();

        return view('auth.dashboard', compact(
            'user',
            'categoriesCount',
            'productsCount'
        ));
    }
}
