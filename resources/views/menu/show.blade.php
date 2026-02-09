<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Cardápio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

</head>

<body class="min-h-screen" style="background: linear-gradient(135deg, {{ $user->color_primary }}15 0%, {{ $user->color_secondary }}15 100%), linear-gradient(to bottom right, #f8fafc, #e2e8f0);">
    <!-- Header do Restaurante -->
    <header class="relative overflow-hidden">
        @if($user->image_banner)
        <div class="relative w-full h-72 md:h-[380px] lg:h-[520px] xl:h-[620px]">
            <!-- Banner de Fundo -->
            <div class="absolute inset-0">
                <img src="{{ Storage::url($user->image_banner) }}"
                    alt="Banner {{ $user->name }}"
                    class="w-full h-full object-cover object-center">
                <!-- Overlay escuro para melhor legibilidade -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            </div>

            <!-- Logo e Nome Sobrepostos -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                @if($user->image_logo)
                <div class="w-20 h-20 md:w-28 md:h-28 lg:w-32 lg:h-32 rounded-full overflow-hidden border-4 border-white/40 shadow-2xl mb-4 floating-animation backdrop-blur-sm">
                    <img src="{{ Storage::url($user->image_logo) }}"
                        alt="Logo {{ $user->name }}"
                        class="w-full h-full object-cover">
                </div>
                @endif

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 drop-shadow-2xl">{{ $user->name }}</h1>

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
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg">{{ $user->name }}</h1>
            </div>
        </div>
        @endif

        <!-- Informações de Contato e Redes Sociais -->
        <div class="bg-white/95 backdrop-blur-sm border-t border-white/20">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex flex-col space-y-4">
                    <!-- Contato Principal -->
                    <div class="flex flex-wrap gap-3 justify-center text-sm">

                        @auth
                        @if(Auth::user()->id == $user->id)
                        <div class="flex items-center bg-gray-100 rounded-full px-2 ">
                            <a href="{{{ url("admin/users/$user->id/edit") }}}"
                                target="_blank"
                                class="group gap-2 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-gray-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20">
                                <i class="fas fa-gear text-2xl group-hover:scale-110 transition-transform duration-300 hover:from-gray-600 hover:to-gray-600 text-gray-600 rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-gray-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20"></i>
                                <span class="text-gray-700 font-medium">Editar Página</span>
                            </a>
                        </div>
                        @endif
                        @endauth

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
                        <span
                            class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 rounded-full px-3 py-2 transition-colors">
                            <i class="fas fa-envelope text-blue-500"></i>
                            <span class="text-gray-700 font-medium">{{ $user->email }}</span>
                        </span>
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
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center mb-6">Categorias</h2>
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($categories as $category)
                    <a
                        href="#cat-{{$category->id }}"
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
        <section id="categoria-{{ $category->name }}" class="mb-8 animate-fade-in">
            <!-- Cabeçalho da Categoria (Clicável) -->
            <div class="bg-white/60 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20 cursor-pointer hover:bg-white/70 transition-all duration-300 category-header"
                onclick="toggleCategory('{{ $category->name }}')"
                data-category="{{ $category->name }}">
                <div class="flex items-center gap-6">
                    <div class="w-6 h-12 rounded-full shadow-lg" style="background: {{ $category->color ?? $user->color_primary }};"></div>
                    <div class="flex-1">
                        <h2 class="text-2xl md:text-3xl font-bold mb-2" style="color: {{ $category->color ?? $user->color_primary }};">{{ $category->name }}</h2>
                        @if($category->description)
                        <p class="text-gray-600 text-sm md:text-base">{!! !empty($category->description) ? $category->description : '' !!}</p>
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
                        <div class="accordion-icon transition-transform duration-300" id="icon-{{ $category->name }}">
                            <i class="fas fa-chevron-down text-2xl" style="color: {{ $category->color ?? $user->color_primary }};"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container de Produtos (Colapsável) -->
            <div class="category-content overflow-hidden transition-all duration-500 ease-in-out"
                id="content-{{ $category->name }}"
                style="max-height: 0; opacity: 0;">
                <div class="pt-8" id="cat-{{$category->id }}">
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
                                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 flex-1 transition-colors" style="color: {{ $category->color ?? $user->color_primary }};">{{ $product->name }}</h3>
                                    <div class="ml-3">
                                        <span class="text-xl md:text-2xl font-bold ml-2" style="color: {{ $category->color ?? $user->color_primary }};">{{ $product->formatted_price }}</span>
                                    </div>
                                </div>
                                <!-- Descrição -->
                                @if($product->description)
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{!! !empty($product->description) ? $product->description : '' !!} </p>
                                @endif

                                <!-- Características -->
                                @if($product->features && count($product->features) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($product->features as $feature)
                                    <span class="inline-block text-xs px-3 py-1 rounded-full font-medium border"
                                        style="color: <?= $category->color ?? $user->color_primary ?>; border-color: <?= $category->color ?? $user->color_primary ?>60;">
                                        {{ $feature }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                <div class="flex flex justify-end gap-2   ">
                                    <button onclick="addToCart({
                                        id: '{{ $product->id }}',
                                        name: '{{ addslashes($product->name) }}',
                                        price: '{{ $product->formatted_price }}',
                                        image: '{{ $product->image }}'
                                    })"
                                        class="inline-block px-4 py-2 rounded-full border bg-gradient-to-r from-green-400 to-green-500 text-white text-base font-bold cursor-pointer hover:shadow-lg transition-all transform hover:scale-105 active:scale-95">
                                        Comprar
                                    </button>
                                </div>

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
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Cardápio em Preparação</h3>
                <p class="text-gray-600 text-base md:text-lg leading-relaxed">Este restaurante ainda está organizando seu cardápio. Volte em breve para descobrir deliciosas opções!</p>
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
        <div class="{{ 'bg-['.$user->color_primary.']' }} ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
                <div class="glass-effect rounded-3xl p-8">
                    <p class="text-white/90 text-sm md:text-lg font-medium">© {{ date('Y') }} <a href="https://phelipecurty.vercel.app" target="_blank" class="text-white/90 hover:text-white/80">Phelipe Curty</a> Cardápio Virtual.</p>
                    <!-- <p class="text-white/70 mt-2 text-sm md:text-base">Desenvolvido com ❤️ para uma experiência gastronômica única</p> -->
                </div>
            </div>
        </div>
    </footer>

    {{-- Botão/Painel de Pagamento (colapsável, não intrusivo) --}}
    @if(!$hasPayment)
    <div id="pay-toggle-{{ $user->id }}" class="fixed bottom-7 z-50 animate-bounce" style="right: 7rem;">
        <form method="POST" action="{{ route('mercadopago.checkout') }}">
            @csrf
            <button id="pay-open-btn-{{ $user->id }}"
                class="group inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white font-bold py-3 px-4 rounded-full shadow-lg hover:shadow-emerald-500/25 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-credit-card text-lg group-hover:scale-110 transition-transform duration-300"></i>
                <span>Pagar assinatura</span>
            </button>
        </form>
    </div>
    @endif

    <!-- Botões Flutuantes de Redes Sociais (Ocultos no Mobile) -->
    <div class="hidden md:flex fixed bottom-6 right-6 flex-col gap-4 z-50">
        @auth
        @if(Auth::user()->id == $user->id)
        <a href="{{{ url("admin/users/$user->id/edit") }}}"
            target="_blank"
            class="group w-16 h-16 bg-gradient-to-b from-gray-500 to-gray-800 hover:from-gray-600 hover:to-gray-600 text-white rounded-2xl flex items-center justify-center shadow-2xl hover:shadow-gray-500/25 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 backdrop-blur-lg border border-white/20">
            <i class="fas fa-gear text-2xl group-hover:scale-110 transition-transform duration-300"></i>
        </a>
        @endif
        @endauth

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
            anchor.addEventListener('click', function(e) {
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
        function toggleCategory(categoryName) {
            const content = document.getElementById(`content-${categoryName}`);
            const icon = document.getElementById(`icon-${categoryName}`);
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
        function expandCategoryFromMenu(categoryName) {
            const content = document.getElementById(`content-${categoryName}`);
            const icon = document.getElementById(`icon-${categoryName}`);
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
                const categoryName = href.replace('#categoria-', '');

                // Expandir a categoria
                setTimeout(() => {
                    expandCategoryFromMenu(categoryName);
                }, 100);
            });
        });
    </script>

    <!-- Order Floating Button -->  
    <div id="order-floating-btn" class="fixed left-6 z-50 cursor-pointer animate-bounce" style="bottom: 110px;" onclick="toggleOrderModal()">
        <div class="bg-green-600 text-white rounded-full p-4 shadow-2xl flex items-center gap-3 hover:bg-green-700 transition-all transform hover:scale-110 border-2 border-white">
            <div class="relative">
                <i class="fa-solid fa-basket-shopping text-xl"></i>
                <span id="order-count" class="absolute -top-2 -right-2 bg-white text-green-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold shadow-sm">0</span>
            </div>
        </div>
    </div>

    <!-- Cart Floating Button -->
    <div id="cart-floating-btn" class="fixed bottom-6 left-6 z-50 cursor-pointer animate-bounce" onclick="toggleCartModal()">
        <div class="bg-red-600 text-white rounded-full p-4 shadow-2xl flex items-center gap-3 hover:bg-red-700 transition-all transform hover:scale-110 border-2 border-white">
            <div class="relative">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-white text-red-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold shadow-sm">0</span>
            </div>
            <span id="cart-total" class="font-bold hidden sm:inline">R$ 0,00</span>
        </div>
    </div>


    <!-- Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="toggleCartModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg pointer-events-auto">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold leading-6 text-gray-900 flex items-center gap-2" id="modal-title">
                                <i class="fas fa-shopping-bag text-red-500"></i> Seu Carrinho
                            </h3>
                            <button onclick="toggleCartModal()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div id="cart-items" class="mt-4 space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                            <!-- Items will be injected here -->
                        </div>

                        <div class="mt-4 space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                            <textarea id="observation" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none " rows="2" placeholder="Adicione uma observação ao pedido..."></textarea>
                        </div>

                        <div class="mt-4 space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar w-full">
                            <input id="client_name" type="text" class="w-full justify-center rounded-xl bg-white px-3 py-3 text-base font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Seu nome">

                            <select id="table_id" class="w-full justify-center rounded-xl bg-white px-3 py-3 text-base font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Selecione sua mesa</option>
                                @foreach($restaurantTables as $table)
                                <option value="{{ $table->id }}" @selected(request()->route('table') == $table->id)>
                                    Mesa {{ $table->number }}
                                </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mt-6 border-t pt-4 bg-gray-50 -mx-6 -mb-4 p-6">
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900 mb-4">
                                <span>Total do Pedido</span>
                                <span id="cart-modal-total" class="text-2xl text-green-600">R$ 0,00</span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">

                                <button type="button" class="inline-flex w-full justify-center rounded-xl bg-white px-3 py-3 text-base font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto" onclick="toggleCartModal()">
                                    Continuar Comprando
                                </button>
                                <button type="button" class="inline-flex w-full justify-center items-center gap-2 rounded-xl bg-green-600 px-3 py-3 text-base font-bold text-white shadow-lg hover:bg-green-500 hover:shadow-green-500/30 transition-all sm:w-auto sm:flex-1" onclick="finalizeOrder()">
                                    Finalizar Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize cart manager
            window.cartManager = new CartManager();
        });

        // UI Logic for Cart
        function addToCart(product) {
            window.cartManager.addItem(product);
            console.log('product', product);
            // Visual feedback on button
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-check"></i> Adicionado';
            btn.classList.remove('from-green-400', 'to-green-500');
            btn.classList.add('bg-green-700');

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.add('from-green-400', 'to-green-500');
                btn.classList.remove('bg-green-700');
            }, 1000);
        }

        function toggleCartModal() {
            const modal = document.getElementById('cart-modal');
            const isHidden = modal.classList.contains('hidden');
            const total = window.cartManager.getTotal();
            document.getElementById('cart-modal-total').textContent = window.cartManager.formatMoney(total);

            if (isHidden) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
                renderCartItems();
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        function renderCartItems() {
            const items = window.cartManager.getItems();
            const container = document.getElementById('cart-items');

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shopping-basket text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Seu carrinho está vazio</p>
                        <p class="text-sm text-gray-400 mt-1">Adicione itens deliciosos para começar!</p>
                    </div>`;
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 flex-1">
                        ${item.image ? 
                            `<img src="/storage/${item.image}" class="w-16 h-16 object-cover rounded-lg shadow-sm">` : 
                            `<div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-utensils text-gray-400"></i></div>`
                        }
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 truncate">${item.name}</h4>
                            <p class="text-green-600 font-semibold">${window.cartManager.formatMoney(item.price)}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-2 ml-4">
                        <button onclick="window.cartManager.removeItem('${item.id}')" class="text-gray-400 hover:text-red-500 transition-colors text-xs uppercase font-bold tracking-wider">
                            Remover
                        </button>
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button onclick="window.cartManager.updateQuantity('${item.id}', ${item.quantity - 1})" class="w-8 h-8 rounded-md bg-white text-gray-600 shadow-sm hover:bg-gray-50 font-bold transition-all disabled:opacity-50">-</button>
                            <span class="font-bold w-10 text-center text-gray-800">${item.quantity}</span>
                            <button onclick="window.cartManager.updateQuantity('${item.id}', ${item.quantity + 1})" class="w-8 h-8 rounded-md bg-white text-green-600 shadow-sm hover:bg-gray-50 font-bold transition-all">+</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function finalizeOrder() {
            // alert('Funcionalidade de finalizar pedido será implementada em breve!');
            const orderData = {
                cart: window.cartManager.getItems(),
                total: window.cartManager.getTotal(),
                table_id: Number(document.getElementById('table_id').value),
                user_id: <?= $user->id ?>,
                client_name: document.getElementById('client_name').value,
                observation: document.getElementById('observation').value
            };

            if (!orderData.table_id) {
                showNotification('Por favor, selecione sua mesa.', 'error');    
                return;
            }

            if (orderData.total === 0 || !orderData.cart.length) {
                showNotification('Carrinho vazio! Adicione itens antes de finalizar.', 'error');    
                return;
            }

            if (!orderData.client_name || orderData.client_name.trim() === '') {
                showNotification('Por favor, insira seu nome.', 'error');    
                return;
            }

            // Show loading state
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

            fetch("{{ route('client.buys') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(orderData),
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.error || data.message || 'Erro ao processar pedido');
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        window.cartManager.clear();
                        toggleCartModal();
                        if (data.url) {
                            window.location.href = data.url;
                        }
                    } else {
                        showNotification(data.error || 'Erro desconhecido', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification(error.message || 'Ocorreu um erro. Por favor, tente novamente.', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            
            console.log(orderData);
        }

        // Listen for cart updates
        window.addEventListener('cart-updated', (e) => {
            const items = e.detail;
            const count = window.cartManager.getCount();
            const total = window.cartManager.getTotal();

            // Update Floating Button
            const floatingBtn = document.getElementById('cart-floating-btn');
            document.getElementById('cart-count').innerText = count;
            document.getElementById('cart-total').innerText = window.cartManager.formatMoney(total);
            document.getElementById('cart-modal-total').innerText = window.cartManager.formatMoney(total);

            if (count > 0) {
                // floatingBtn.classList.remove('hidden');
                floatingBtn.classList.add('flex');
            } else {
                // floatingBtn.classList.add('hidden');
                floatingBtn.classList.remove('flex');

                // Close modal if it was open and now empty? 
                // Let's keep it open so user sees "empty" state, unless they removed the last item explicitly.
            }

            // If modal is open, re-render
            const modal = document.getElementById('cart-modal');
            if (!modal.classList.contains('hidden')) {
                renderCartItems();
            }
        });
    </script>

    <!-- Loader Script -->
    <!-- <script src="{{ asset('js/loader.js') }}"></script> -->

    <script src="{{ asset('js/cart.js') }}"></script>

    <script>
        function showNotification(message, type = 'info') {
            const config = {
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            };

            switch (type) {
                case 'success':
                    config.icon = 'success';
                    break;
                case 'error':
                    config.icon = 'error';
                    break;
                case 'warning':
                    config.icon = 'warning';
                    break;
                default:
                    config.icon = 'info';
            }

            Swal.fire(config);
        }
    </script>
</body>

</html>