<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redireciona baseado na role do usuário
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {
                return redirect()->intended('/admin');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Exibe o formulário de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Processa o registro de novo usuário
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'celphone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Gera um slug único baseado no nome
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'slug' => $slug,
            'celphone' => $request->celphone,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'role' => 'user', // Define automaticamente como usuário comum
            'color_primary' => '#3B82F6', // Azul padrão
            'color_secondary' => '#1E40AF', // Azul escuro padrão
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Conta criada com sucesso! Bem-vindo(a) ao sistema.');
    }

    /**
     * Processa o logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Exibe o dashboard do usuário
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Conta estatísticas básicas
        $categoriesCount = $user->categories()->count();
        $productsCount = $user->categories()->withCount('products')->get()->sum('products_count');

        return view('auth.dashboard', compact('user', 'categoriesCount', 'productsCount'));
    }
}