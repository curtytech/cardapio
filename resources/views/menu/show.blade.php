<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Cardápio</title>
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
<body class="min-h-screen" style="background: linear-gradient(135deg, {{ $user->color_primary }}15 0%, {{ $user->color_secondary }}15 100%), linear-gradient(to bottom right, #f8fafc, #e2e8f0);">
    <!-- Header do Restaurante -->
    <header class="relative overflow-hidden">
        <!-- Banner com Logo e Nome Sobrepostos -->
        @if($user->image_banner)
            <div class="relative w-full h-64 md:h-80 lg:h-96">
                <!-- Banner de Fundo -->
                <div class="absolute inset-0">
                    <img src="{{ Storage::url($user->image_banner) }}" 
                         alt="Banner {{ $user->name }}" 
                         class="w-full h-full object-cover">
                    <!-- Overlay escuro para melhor legibilidade -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                </div>
                
                <!-- Logo e Nome Sobrepostos -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                    <!-- Logo do Restaurante -->
                    @if($user->image_logo)
                        <div class="w-20 h-20 md:w-28 md:h-28 lg:w-32 lg:h-32 rounded-full overflow-hidden border-4 border-white/40 shadow-2xl mb-4 floating-animation backdrop-blur-sm">
                            <img src="{{ Storage::url($user->image_logo) }}" 
                                 alt="Logo {{ $user->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    <!-- Nome do Restaurante -->
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 drop-shadow-2xl">{{ $user->name }}</h1>
                    
                    <!-- Informações Básicas -->
                    @if($user->address || $user->city)
                        <p class="text-white/90 text-sm md:text-base font-medium drop-shadow-lg">
                            @if($user->city){{ $user->city }}@endif
                            @if($user->address && $user->city) • @endif
                            @if($user->address){{ $user->address }}@endif
                        </p>
                    @endif
                </div>
            </div>
        @else
            <!-- Fallback caso não tenha banner -->
            <div class="gradient-bg py-16">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    @if($user->image_logo)
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-white/30 shadow-2xl mx-auto mb-6 floating-animation">
                            <img src="{{ Storage::url($user->image_logo) }}" 
                                 alt="Logo {{ $user->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg">{{ $user->name }}</h1>
                </div>
            </div>
        @endif
        
        <!-- Informações de Contato e Redes Sociais -->
        <div class="bg-white/95 backdrop-blur-sm border-t border-white/20">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex flex-col space-y-4">
                    <!-- Contato Principal -->
                    <div class="flex flex-wrap gap-3 justify-center text-sm">
                        @if($user->celphone)
                            <div class="flex items-center gap-2 bg-gray-100 rounded-full px-3 py-2">
                                <i class="fas fa-phone text-emerald-500"></i>
                                <span class="text-gray-700 font-medium">{{ $user->celphone }}</span>
                            </div>
                        @endif
                        
                        @if($user->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp) }}" 
                               target="_blank"
                               class="flex items-center gap-2 bg-green-50 hover:bg-green-100 rounded-full px-3 py-2 transition-colors">
                                <i class="fab fa-whatsapp text-green-500"></i>
                                <span class="text-gray-700 font-medium">{{ $user->whatsapp }}</span>
                            </a>
                        @endif
                        
                        @if($user->email)
                            <a href="mailto:{{ $user->email }}" 
                               class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 rounded-full px-3 py-2 transition-colors">
                                <i class="fas fa-envelope text-blue-500"></i>
                                <span class="text-gray-700 font-medium">{{ $user->email }}</span>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Redes Sociais -->
                    @if($user->instagram || $user->facebook)
                        <div class="flex gap-3 justify-center">
                            @if($user->instagram)
                                <a href="https://instagram.com/{{ $user->instagram }}" 
                                   target="_blank"
                                   class="group flex items-center gap-2 bg-pink-50 hover:bg-pink-100 rounded-full px-3 py-2 transition-all duration-300 transform hover:scale-105">
                                    <i class="fab fa-instagram text-pink-500 group-hover:text-pink-600"></i>
                                    <span class="text-gray-700 font-medium text-sm">Instagram</span>
                                </a>
                            @endif
                            
                            @if($user->facebook)
                                <a href="{{ $user->facebook }}" 
                                   target="_blank"
                                   class="group flex items-center gap-2 bg-blue-50 hover:bg-blue-100 rounded-full px-3 py-2 transition-all duration-300 transform hover:scale-105">
                                    <i class="fab fa-facebook-f text-blue-500 group-hover:text-blue-600"></i>
                                    <span class="text-gray-700 font-medium text-sm">Facebook</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informações Detalhadas (se necessário) -->
        @if($user->address || $user->neighborhood || $user->city || $user->state)
            <div class="bg-gray-50 border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 py-4">
                    <div class="flex items-start gap-3 text-gray-600 justify-center text-center">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1"></i>
                        <div class="text-sm">
                            @if($user->address)
                                <span class="font-medium">{{ $user->address }}</span>
                                @if($user->number), {{ $user->number }}@endif
                                @if($user->complement) - {{ $user->complement }}@endif
                                <br>
                            @endif
                            @if($user->neighborhood || $user->city || $user->state)
                                <span class="text-gray-500">
                                    @if($user->neighborhood){{ $user->neighborhood }}@endif
                                    @if($user->neighborhood && ($user->city || $user->state)), @endif
                                    @if($user->city){{ $user->city }}@endif
                                    @if($user->city && $user->state) - @endif
                                    @if($user->state){{ $user->state }}@endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </header>
    


    <!-- Conteúdo Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($categories->count() > 0)
            <!-- Menu de Navegação das Categorias -->
            <nav class="mb-12 animate-fade-in">
                <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-6 shadow-xl border border-white/20">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Categorias</h2>
                    <div class="flex flex-wrap gap-3 justify-center">
                        @foreach($categories as $category)
                            <a href="#categoria-{{ $category->slug }}" 
                               class="group inline-flex items-center px-6 py-3 rounded-2xl text-sm font-semibold transition-all duration-300 hover:scale-105 hover:shadow-lg card-hover"
                               style="background: color: {{ $category->color ?? $user->color_primary }}; border: 2px solid {{ $category->color }}60;">
                                <i class="fas fa-utensils mr-2 group-hover:rotate-12 transition-transform duration-300" style="color: {{ $category->color ?? $user->color_primary }};"></i>
                                {{ $category->name }}
                                <span class="ml-3 bg-white/90 text-gray-700 rounded-full px-3 py-1 text-xs font-bold shadow-sm">{{ $category->products->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>

            <!-- Categorias e Produtos -->
            @foreach($categories as $category)
                <section id="categoria-{{ $category->slug }}" class="mb-8 animate-fade-in">
                    <!-- Cabeçalho da Categoria (Clicável) -->
                    <div class="bg-white/60 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20 cursor-pointer hover:bg-white/70 transition-all duration-300 category-header" 
                         onclick="toggleCategory('{{ $category->slug }}')" 
                         data-category="{{ $category->slug }}">
                        <div class="flex items-center gap-6">
                            <div class="w-6 h-12 rounded-full shadow-lg" style="background: {{ $category->color ?? $user->color_primary }};"></div>
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold mb-2" style="color: {{ $category->color ?? $user->color_primary }};">{{ $category->name }}</h2>
                                @if($category->description)
                                    <p class="text-gray-600 text-lg">{{ $category->description }}</p>
                                @endif
                                <div class="text-sm text-gray-500 mt-2">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-box mr-1"></i>
                                        {{ $category->products->count() }} {{ $category->products->count() === 1 ? 'produto' : 'produtos' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="hidden md:block">
                                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-utensils text-2xl" style="color: {{ $category->color ?? $user->color_primary }};"></i>
                                    </div>
                                </div>
                                <!-- Ícone de Expansão/Colapso -->
                                <div class="accordion-icon transition-transform duration-300" id="icon-{{ $category->slug }}">
                                    <i class="fas fa-chevron-down text-2xl" style="color: {{ $category->color ?? $user->color_primary }};"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Container de Produtos (Colapsável) -->
                    <div class="category-content overflow-hidden transition-all duration-500 ease-in-out" 
                         id="content-{{ $category->slug }}" 
                         style="max-height: 0; opacity: 0;">
                        <div class="pt-8">
                            <!-- Grid de Produtos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                @foreach($category->products as $product)
                                    <div class="group bg-white/70 backdrop-blur-lg rounded-3xl shadow-xl overflow-hidden border border-white/20 card-hover">
                                        <!-- Imagem do Produto -->
                                        @if($product->image)
                                            <div class="h-56 overflow-hidden relative">
                                                <img src="{{ Storage::url($product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                            </div>
                                        @endif

                                        <div class="p-6">
                                            <!-- Nome e Preço -->
                                            <div class="flex justify-between items-start mb-3">
                                                <h3 class="text-xl font-bold text-gray-800 flex-1 transition-colors" style="color: {{ $category->color ?? $user->color_primary }};">{{ $product->name }}</h3>
                                                <div class="ml-3">
                                                    <span class="text-2xl font-bold ml-2" style="color: {{ $category->color ?? $user->color_primary }};">{{ $product->formatted_price }}</span>
                                                </div>
                                            </div>

                                            <!-- Descrição -->
                                            @if($product->description)
                                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $product->description }}</p>
                                            @endif

                                            <!-- Características -->
                                            @if($product->features && count($product->features) > 0)
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    @foreach($product->features as $feature)
                                                        <span class="inline-block text-xs px-3 py-1 rounded-full font-medium border"
                                                              style="color: {{ $category->color ?? $user->color_primary }}; border-color: {{ $category->color ?? $user->color_primary }}60;">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                           
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endforeach
        @else
            <!-- Mensagem quando não há produtos -->
            <div class="text-center py-16 animate-fade-in">
                <div class="max-w-lg mx-auto bg-white/60 backdrop-blur-lg rounded-3xl p-12 shadow-xl border border-white/20">
                    <div class="floating-animation">
                        <i class="fas fa-utensils text-8xl text-indigo-300 mb-6"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">Cardápio em Preparação</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">Este restaurante ainda está organizando seu cardápio. Volte em breve para descobrir deliciosas opções!</p>
                    <div class="mt-8">
                        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-6 py-3 rounded-full font-medium">
                            <i class="fas fa-clock"></i>
                            <span>Em breve novidades</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="relative overflow-hidden">
        <div class="gradient-bg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
                <div class="glass-effect rounded-3xl p-8">
                    <p class="text-white/90 text-lg font-medium">© {{ date('Y') }} {{ $user->name }}. Cardápio Digital.</p>
                    <p class="text-white/70 mt-2">Desenvolvido com ❤️ para uma experiência gastronômica única</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botões Flutuantes de Redes Sociais (Ocultos no Mobile) -->
    <div class="hidden md:flex fixed bottom-6 right-6 flex-col gap-4 z-50">
        @if($user->whatsapp)
            <a href="https://wa.me/{{ $user->whatsapp }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-green-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20">
                <i class="fab fa-whatsapp text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
        
        @if($user->instagram)
            <a href="https://instagram.com/{{ $user->instagram }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-purple-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20">
                <i class="fab fa-instagram text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
        
        @if($user->facebook)
            <a href="{{ $user->facebook }}" 
               target="_blank"
               class="group w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-blue-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20">
                <i class="fab fa-facebook-f text-2xl group-hover:scale-110 transition-transform duration-300"></i>
            </a>
        @endif
    </div>

    <!-- Script para scroll suave -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Função para controlar o accordion das categorias
        function toggleCategory(categorySlug) {
            const content = document.getElementById(`content-${categorySlug}`);
            const icon = document.getElementById(`icon-${categorySlug}`);
            const chevron = icon.querySelector('i');
            
            if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
                // Expandir
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                chevron.classList.remove('fa-chevron-down');
                chevron.classList.add('fa-chevron-up');
                icon.style.transform = 'rotate(180deg)';
            } else {
                // Colapsar
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                chevron.classList.remove('fa-chevron-up');
                chevron.classList.add('fa-chevron-down');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Inicializar todas as categorias como fechadas
        document.addEventListener('DOMContentLoaded', function() {
            const categoryContents = document.querySelectorAll('.category-content');
            categoryContents.forEach(content => {
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
            });
        });

        // Função para expandir categoria quando clicada no menu lateral
        function expandCategoryFromMenu(categorySlug) {
            const content = document.getElementById(`content-${categorySlug}`);
            const icon = document.getElementById(`icon-${categorySlug}`);
            const chevron = icon.querySelector('i');
            
            if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                chevron.classList.remove('fa-chevron-down');
                chevron.classList.add('fa-chevron-up');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Modificar os links do menu lateral para expandir a categoria
        document.querySelectorAll('a[href^="#categoria-"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const categorySlug = href.replace('#categoria-', '');
                
                // Expandir a categoria
                setTimeout(() => {
                    expandCategoryFromMenu(categorySlug);
                }, 100);
            });
        });
    </script>
    
    <!-- Loader Script -->
    <script src="{{ asset('js/loader.js') }}"></script>
</body>
</html>