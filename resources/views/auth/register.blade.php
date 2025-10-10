<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Cardápio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-800 to-indigo-700">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <!-- Logo/Header -->
            <div class="text-center animate-fade-in">
                <div class="mx-auto h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mb-6 floating-animation">
                    <i class="fas fa-user-plus text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Crie sua conta</h2>
                <p class="text-white/80">Comece a criar seu cardápio virtual hoje mesmo</p>
            </div>

            <!-- Formulário de Registro -->
            <div class="glass-effect rounded-2xl p-8 shadow-2xl animate-fade-in">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Informações Básicas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-user mr-2"></i>Nome do Restaurante *
                            </label>
                            <input type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="Nome do seu restaurante">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-envelope mr-2"></i>E-mail *
                            </label>
                            <input type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="seu@email.com">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Senhas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Senha -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-lock mr-2"></i>Senha *
                            </label>
                            <input type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="••••••••">
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Senha -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-lock mr-2"></i>Confirmar Senha *
                            </label>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Contatos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Telefone -->
                        <div>
                            <label for="celphone" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-phone mr-2"></i>Telefone
                            </label>
                            <input type="text"
                                id="celphone"
                                name="celphone"
                                value="{{ old('celphone') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="(11) 99999-9999">
                            @error('celphone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-white mb-2">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </label>
                            <input type="text"
                                id="whatsapp"
                                name="whatsapp"
                                value="{{ old('whatsapp') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="(11) 99999-9999">
                            @error('whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Endereço
                        </label>
                        <input type="text"
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                            placeholder="Rua, número, bairro">
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cidade e Estado -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cidade -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-city mr-2"></i>Cidade
                            </label>
                            <input type="text"
                                id="city"
                                name="city"
                                value="{{ old('city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="Sua cidade">
                            @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-white mb-2">
                                <i class="fas fa-flag mr-2"></i>Estado
                            </label>
                            <input type="text"
                                id="state"
                                name="state"
                                value="{{ old('state') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="SP">
                            @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Redes Sociais -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Instagram -->
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-white mb-2">
                                <i class="fab fa-instagram mr-2"></i>Instagram
                            </label>
                            <input type="text"
                                id="instagram"
                                name="instagram"
                                value="{{ old('instagram') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="@seurestaurante">
                            @error('instagram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facebook -->
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-white mb-2">
                                <i class="fab fa-facebook mr-2"></i>Facebook
                            </label>
                            <input type="url"
                                id="facebook"
                                name="facebook"
                                value="{{ old('facebook') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/90 backdrop-blur-sm"
                                placeholder="https://facebook.com/seurestaurante">
                            @error('facebook')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botão de Registro -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta
                    </button>
                </form>

                <!-- Link para Login -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Já tem uma conta?
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-300">
                            Faça login aqui
                        </a>
                    </p>
                </div>
            </div>

            <!-- Link para Home -->
            <div class="text-center animate-fade-in">
                <a href="{{ route('home') }}" class="text-white/80 hover:text-white transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar ao início
                </a>
            </div>
        </div>
    </div>
</body>

</html>