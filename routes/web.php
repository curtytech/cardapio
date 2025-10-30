<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MercadoPagoController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registro
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Checkout
    Route::post('/checkout/mercadopago', [MercadoPagoController::class, 'checkout'])->name('mercadopago.checkout');
});

// Webhook fica na routes/api.php

// Rota para exibir o cardápio completo de um usuário pelo slug
Route::get('/{slug}', [MenuController::class, 'show'])->name('menu.show');

// Rota para exibir produtos de uma categoria específica do usuário
// Route::get('/menu/{userSlug}/{categorySlug}', [MenuController::class, 'category'])->name('menu.category');
