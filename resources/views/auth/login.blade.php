<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-800 to-indigo-700 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8 fade-in">
            <div class="floating">
                <i class="fas fa-utensils text-6xl text-white mb-4"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ config('app.name') }}</h1>
            <p class="text-white">Faça login em sua conta</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl fade-in">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all duration-300"
                           placeholder="seu@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-lock mr-2"></i>Senha
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all duration-300"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-white">
                        Lembrar de mim
                    </label>
                </div>

                <!-- Submit Button -->
                <!-- <button type="submit" 
                        class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-transparent">
                    <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                </button> -->
                   <button type="submit"
                        class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Entrar
                    </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center space-y-2">
                <p class="text-white/80">
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" class="text-amber-300 hover:text-amber-200 font-medium transition-colors duration-300">
                        Registre-se aqui
                    </a>
                </p>
                <a href="{{ route('home') }}" class="inline-block text-white/60 hover:text-white transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-1"></i>Voltar ao início
                </a>
            </div>
        </div>
    </div>
    
    <!-- Loader Script -->
    <script src="{{ asset('js/loader.js') }}"></script>
</body>
</html>