<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .gradient-bg {
            background: linear-gradient(135deg, {{ $user->color_primary ?? '#667eea' }} 0%, {{ $user->color_secondary ?? '#764ba2' }} 100%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="min-h-screen" >
    <!-- Banner com Logo e Nome Sobrepostos -->
    @if($user->image_banner)
    <section class="relative h-64 md:h-80 overflow-hidden">
        <!-- Banner de Fundo -->
        <div class="absolute inset-0">
            <img src="{{ Storage::url($user->image_banner) }}"
                 alt="Banner {{ $user->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        
        <!-- Conteúdo Sobreposto -->
        <div class="relative h-full flex flex-col justify-between p-4 md:p-6">
            <!-- Botão Voltar -->
            <div class="flex justify-start">
                <a href="{{ route('menu.show', $user->slug) }}" 
                   class="inline-flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-white/30 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Cardápio
                </a>
            </div>
            
            <!-- Logo e Nome Centralizados -->
            <div class="flex flex-col items-center justify-center text-center">
                @if($user->image_logo)
                    <div class="mb-4">
                        <img src="{{ Storage::url($user->image_logo) }}"
                             alt="Logo {{ $user->name }}"
                             class="w-20 h-20 md:w-24 md:h-24 rounded-full shadow-2xl border-4 border-white/50 backdrop-blur-sm object-cover">
                    </div>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-2xl mb-2">{{ $user->name }}</h1>
                @if($user->address)
                    <p class="text-white/90 text-sm md:text-base drop-shadow-lg">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $user->address }}
                    </p>
                @endif
            </div>
            
            <!-- Espaço para manter o layout equilibrado -->
            <div></div>
        </div>
    </section>
    @else
    <!-- Fallback caso não tenha banner -->
    <section class="relative h-64 md:h-80 overflow-hidden bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700">
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Conteúdo Sobreposto -->
        <div class="relative h-full flex flex-col justify-between p-4 md:p-6">
            <!-- Botão Voltar -->
            <div class="flex justify-start">
                <a href="{{ route('menu.show', $user->slug) }}" 
                   class="inline-flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-white/30 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Cardápio
                </a>
            </div>
            
            <!-- Logo e Nome Centralizados -->
            <div class="flex flex-col items-center justify-center text-center">
                @if($user->image_logo)
                    <div class="mb-4">
                        <img src="{{ Storage::url($user->image_logo) }}"
                             alt="Logo {{ $user->name }}"
                             class="w-20 h-20 md:w-24 md:h-24 rounded-full shadow-2xl border-4 border-white/50 backdrop-blur-sm object-cover">
                    </div>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-2xl mb-2">{{ $user->name }}</h1>
                @if($user->address)
                    <p class="text-white/90 text-sm md:text-base drop-shadow-lg">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $user->address }}
                    </p>
                @endif
            </div>
            
            <!-- Espaço para manter o layout equilibrado -->
            <div></div>
        </div>
    </section>
    @endif

    <!-- Informações de Contato e Redes Sociais -->
    <section class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-3 justify-center text-sm">
                @if($user->celphone)
                    <div class="flex items-center gap-2 bg-white/60 rounded-full px-3 py-1 backdrop-blur-sm shadow-sm">
                        <i class="fas fa-phone text-emerald-500"></i>
                        <span class="text-gray-700 font-medium">{{ $user->celphone }}</span>
                    </div>
                @endif
                
                @if($user->whatsapp)
                    <div class="flex items-center gap-2 bg-white/60 rounded-full px-3 py-1 backdrop-blur-sm shadow-sm">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp) }}" 
                           target="_blank" 
                           class="text-gray-700 hover:text-green-600 transition-colors font-medium">
                            {{ $user->whatsapp }}
                        </a>
                    </div>
                @endif
                
                @if($user->email)
                    <div class="flex items-center gap-2 bg-white/60 rounded-full px-3 py-1 backdrop-blur-sm shadow-sm">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <a href="mailto:{{ $user->email }}" 
                           class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            {{ $user->email }}
                        </a>
                    </div>
                @endif
                
                @if($user->instagram)
                    <a href="https://instagram.com/{{ $user->instagram }}" 
                       target="_blank"
                       class="group flex items-center gap-2 bg-white/60 rounded-full px-3 py-1 backdrop-blur-sm hover:bg-pink-500/20 transition-all duration-300 transform hover:scale-105 shadow-sm">
                        <i class="fab fa-instagram text-pink-500 group-hover:text-pink-600"></i>
                        <span class="text-gray-700 font-medium">Instagram</span>
                    </a>
                @endif
                
                @if($user->facebook)
                    <a href="{{ $user->facebook }}" 
                       target="_blank"
                       class="group flex items-center gap-2 bg-white/60 rounded-full px-3 py-1 backdrop-blur-sm hover:bg-blue-500/20 transition-all duration-300 transform hover:scale-105 shadow-sm">
                        <i class="fab fa-facebook-f text-blue-500 group-hover:text-blue-600"></i>
                        <span class="text-gray-700 font-medium">Facebook</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Cabeçalho da Categoria -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="glass-effect backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20 animate-fade-in">
                <div class="flex items-center gap-6">
                    <div class="w-8 h-16 rounded-2xl shadow-lg floating-animation" 
                         ></div>
                    <div class="flex-1">
                        <h2 class="text-4xl font-bold mb-2 drop-shadow-lg" style="color: {{ $category->color }};">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="text-gray-700 text-lg mb-2">{{ $category->description }}</p>
                        @endif
                        <div class="inline-flex items-center bg-white/30 backdrop-blur-sm px-4 py-2 rounded-full">
                            <i class="fas fa-utensils mr-2" style="color: {{ $category->color }};"></i>
                            <span class="text-gray-700 font-medium">{{ $products->count() }} {{ $products->count() === 1 ? 'produto' : 'produtos' }}</span>
                        </div>
                    </div>
                    <!-- Botão de Toggle para Produtos -->
                    <div class="cursor-pointer" onclick="toggleProducts()" id="products-toggle">
                        <div class="accordion-icon transition-transform duration-300">
                            <i class="fas fa-chevron-down text-3xl" style="color: {{ $category->color }};"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conteúdo Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($products->count() > 0)
            <!-- Container de Produtos (Colapsável) -->
            <div class="products-content overflow-hidden transition-all duration-500 ease-in-out" 
                 id="products-content" 
                 style="max-height: 0; opacity: 0;">
                <!-- Grid de Produtos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="group bg-white/70 backdrop-blur-lg rounded-3xl shadow-xl border border-white/20 overflow-hidden card-hover animate-fade-in">
                        <!-- Imagem do Produto -->
                        @if($product->image)
                            <div class="h-56 overflow-hidden relative">
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        @else
                            <div class="h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-5xl text-gray-400 group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Nome e Preço -->
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800 flex-1 group-hover:text-indigo-600 transition-colors duration-300">{{ $product->name }}</h3>
                                <span class="text-2xl font-bold" style="background: linear-gradient(135deg, {{ $user->color_primary }}, {{ $user->color_secondary }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $product->formatted_price }}</span>
                            </div>

                            <!-- Descrição -->
                            @if($product->description)
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $product->description }}</p>
                            @endif

                            <!-- Características -->
                            @if($product->features && count($product->features) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($product->features as $feature)
                                        <span class="inline-block bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 text-xs px-3 py-1 rounded-full font-medium border border-indigo-200 hover:scale-105 transition-transform duration-200">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Código de Barras -->
                            @if($product->barcode)
                                <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2 border-t">
                                    <i class="fas fa-barcode text-gray-500"></i>
                                    <span class="text-xs text-gray-600 font-mono">{{ $product->barcode }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        @else
            <!-- Mensagem quando não há produtos -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto glass-effect backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20 animate-fade-in">
                    <div class="floating-animation mb-6">
                        <i class="fas fa-box-open text-7xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Nenhum Produto Encontrado</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Esta categoria ainda não possui produtos disponíveis.</p>
                    <div class="inline-flex items-center bg-gradient-to-r from-orange-400 to-pink-400 text-white px-3 py-1 rounded-full text-sm font-medium mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        Em breve novidades
                    </div>
                    <div>
                        <a href="{{ route('menu.show', $user->slug) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar ao Cardápio
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="relative overflow-hidden mt-16">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900"></div>
        <div class="relative glass-effect backdrop-blur-lg border-t border-white/10 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="floating-animation">
                    <i class="fas fa-utensils text-3xl text-white/70 mb-4"></i>
                </div>
                <p class="text-white/90 text-lg font-medium">© {{ date('Y') }} {{ $user->name }}</p>
                <p class="text-white/60 mt-2">Desenvolvido com ❤️ para uma experiência gastronômica única</p>
            </div>
        </div>
    </footer>

    <!-- Botões Flutuantes de Redes Sociais (Ocultos no Mobile) -->
    <div class="hidden md:flex fixed bottom-6 right-6 flex-col gap-4 z-50">
        @if($user->whatsapp)
            <a href="https://wa.me/{{ $user->whatsapp }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-green-500/25 backdrop-blur-lg border border-white/20 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                <i class="fab fa-whatsapp text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
        
        @if($user->instagram)
            <a href="https://instagram.com/{{ $user->instagram }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-purple-500/25 backdrop-blur-lg border border-white/20 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                <i class="fab fa-instagram text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
        
        @if($user->facebook)
            <a href="{{ $user->facebook }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-blue-500/25 backdrop-blur-lg border border-white/20 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                <i class="fab fa-facebook-f text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
    </div>

    <!-- Script para controlar o accordion -->
    <script>
        // Função para controlar o accordion dos produtos
        function toggleProducts() {
            const content = document.getElementById('products-content');
            const toggle = document.getElementById('products-toggle');
            const chevron = toggle.querySelector('i');
            
            if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
                // Expandir
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                chevron.classList.remove('fa-chevron-down');
                chevron.classList.add('fa-chevron-up');
                toggle.style.transform = 'rotate(180deg)';
            } else {
                // Colapsar
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                chevron.classList.remove('fa-chevron-up');
                chevron.classList.add('fa-chevron-down');
                toggle.style.transform = 'rotate(0deg)';
            }
        }

        // Inicializar produtos como fechados
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('products-content');
            if (content) {
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
            }
        });
    </script>
    
    <!-- Loader Script -->
    <script src="{{ asset('js/loader.js') }}"></script>
</body>
</html>