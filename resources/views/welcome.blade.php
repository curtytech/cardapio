<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema de Cardápio Virtual') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700">
    <!-- Header -->
    <header class="hidden md:absolute md:top-0 md:w-full md:z-10 md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-white text-lg"></i>
                    </div>
                    <h1 class="text-xl font-bold text-white">Cardápio Virtual</h1>
                </div>
                
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-white hover:text-white/80 px-4 py-2 transition-colors duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300 backdrop-blur-sm">
                                    <i class="fas fa-user-plus mr-2"></i>Cadastrar
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Hero Section -->
            <div class="animate-fade-in">
                <!-- Logo Principal -->
                <div class="mx-auto h-32 w-32 bg-white/20 rounded-full flex items-center justify-center floating-animation mt-10">
                    <i class="fas fa-utensils text-5xl text-white"></i>
                </div>
                
                <!-- Título Principal -->
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 floating-animation">
                    Cardápio
                    <span class="block gradient-text bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                        Virtual
                    </span>
                </h1>
                
                <!-- Subtítulo -->
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Crie e gerencie seu cardápio virtual de forma simples e moderna. 
                    Atraia mais clientes com uma apresentação profissional.
                </p>
                
                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    @guest
                        <a href="{{ route('register') }}" 
                           class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>Começar Agora - Grátis
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="glass-effect text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>Já tenho conta
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i>Ir para Dashboard
                        </a>
                    @endguest
                </div>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16 animate-fade-in mb-5">
                <div class="glass-effect rounded-xl p-6 text-center">
                    <div class="h-16 w-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-2xl text-blue-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Responsivo</h3>
                    <p class="text-white/80">Funciona perfeitamente em celulares, tablets e computadores</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-center">
                    <div class="h-16 w-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-edit text-2xl text-green-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Fácil de Usar</h3>
                    <p class="text-white/80">Interface intuitiva para gerenciar produtos e categorias</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-center">
                    <div class="h-16 w-16 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-share-alt text-2xl text-orange-300"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Compartilhável</h3>
                    <p class="text-white/80">Compartilhe seu cardápio com um link único e personalizado</p>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Loader Script -->
    <script src="{{ asset('js/loader.js') }}"></script>
</body>
</html>
