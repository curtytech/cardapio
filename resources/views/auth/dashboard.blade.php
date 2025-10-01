<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
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
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-800 to-indigo-700">
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-utensils text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-white/70 text-sm">Dashboard</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Link para ver cardápio -->
                    <a href="{{ route('menu.show', $user->slug) }}" 
                       target="_blank"
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-external-link-alt mr-2"></i>Ver Cardápio
                    </a>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensagem de Sucesso -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total de Categorias -->
            <div class="glass-effect rounded-xl p-6 card-hover animate-fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 text-blue-300">
                        <i class="fas fa-list text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-white/70 text-sm">Categorias</p>
                        <p class="text-2xl font-bold text-white">{{ $categoriesCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Total de Produtos -->
            <div class="glass-effect rounded-xl p-6 card-hover animate-fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 text-green-300">
                        <i class="fas fa-utensils text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-white/70 text-sm">Produtos</p>
                        <p class="text-2xl font-bold text-white">{{ $productsCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="glass-effect rounded-xl p-6 card-hover animate-fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 text-purple-300">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-white/70 text-sm">Status</p>
                        <p class="text-2xl font-bold text-white">Ativo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="glass-effect rounded-xl p-8 animate-fade-in">
            <h2 class="text-2xl font-bold text-white mb-6">
                <i class="fas fa-bolt mr-2"></i>Ações Rápidas
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Gerenciar Categorias -->
                <a href="/admin/categories" 
                   class="bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-lg p-6 text-center transition-all duration-300 card-hover">
                    <i class="fas fa-list text-3xl text-blue-300 mb-3"></i>
                    <h3 class="text-white font-medium">Categorias</h3>
                    <p class="text-white/70 text-sm mt-1">Gerenciar categorias</p>
                </a>

                <!-- Gerenciar Produtos -->
                <a href="/admin/products" 
                   class="bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 rounded-lg p-6 text-center transition-all duration-300 card-hover">
                    <i class="fas fa-utensils text-3xl text-green-300 mb-3"></i>
                    <h3 class="text-white font-medium">Produtos</h3>
                    <p class="text-white/70 text-sm mt-1">Gerenciar produtos</p>
                </a>

                <!-- Configurações -->
                <a href="/admin/users/{{ $user->id }}/edit" 
                   class="bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 rounded-lg p-6 text-center transition-all duration-300 card-hover">
                    <i class="fas fa-cog text-3xl text-purple-300 mb-3"></i>
                    <h3 class="text-white font-medium">Configurações</h3>
                    <p class="text-white/70 text-sm mt-1">Editar perfil</p>
                </a>

                <!-- Ver Cardápio -->
                <a href="{{ route('menu.show', $user->slug) }}" 
                   target="_blank"
                   class="bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/30 rounded-lg p-6 text-center transition-all duration-300 card-hover">
                    <i class="fas fa-external-link-alt text-3xl text-orange-300 mb-3"></i>
                    <h3 class="text-white font-medium">Ver Cardápio</h3>
                    <p class="text-white/70 text-sm mt-1">Visualizar público</p>
                </a>
            </div>
        </div>

        <!-- Informações do Usuário -->
        <div class="glass-effect rounded-xl p-8 mt-8 animate-fade-in">
            <h2 class="text-2xl font-bold text-white mb-6">
                <i class="fas fa-user mr-2"></i>Informações da Conta
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-white font-medium mb-4">Dados Básicos</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-white/80">
                            <i class="fas fa-store w-5"></i>
                            <span class="ml-3">{{ $user->name }}</span>
                        </div>
                        <div class="flex items-center text-white/80">
                            <i class="fas fa-envelope w-5"></i>
                            <span class="ml-3">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center text-white/80">
                            <i class="fas fa-link w-5"></i>
                            <span class="ml-3">{{ url('/menu/' . $user->slug) }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-white font-medium mb-4">Contato</h3>
                    <div class="space-y-3">
                        @if($user->celphone)
                            <div class="flex items-center text-white/80">
                                <i class="fas fa-phone w-5"></i>
                                <span class="ml-3">{{ $user->celphone }}</span>
                            </div>
                        @endif
                        @if($user->whatsapp)
                            <div class="flex items-center text-white/80">
                                <i class="fab fa-whatsapp w-5"></i>
                                <span class="ml-3">{{ $user->whatsapp }}</span>
                            </div>
                        @endif
                        @if($user->address)
                            <div class="flex items-center text-white/80">
                                <i class="fas fa-map-marker-alt w-5"></i>
                                <span class="ml-3">{{ $user->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Loader Script -->
    <script src="{{ asset('js/loader.js') }}"></script>
</body>
</html>