<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\DashboardController;


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

    // Print Sell
    Route::get('/sells/{sell}/print', [\App\Http\Controllers\SellController::class, 'print'])->name('sells.print');
});

// Webhook fica na routes/api.php

Route::get('/{slug}/delivery', [MenuController::class, 'delivery'])->name('menu.delivery');

Route::get('/{slug}/{tableNumber?}', [MenuController::class, 'show'])->name('menu.show');

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
