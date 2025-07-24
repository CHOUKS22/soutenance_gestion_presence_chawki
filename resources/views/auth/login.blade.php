<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - IFRAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #3B4F8C 0%, #2C3E50 100%);
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Côté gauche - Formulaire de connexion -->
    <div class="w-full md:w-1/2 flex flex-col bg-white">
        <!-- Logo en haut à gauche -->
        <div class="p-8 pb-0">
            <div class="flex items-center">
                @if(file_exists(public_path('images/logos/ifran.jpeg')))
                    <img src="{{ asset('images/logos/ifran.jpeg') }}" alt="IFRAN Logo" class="h-14">
                @else
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">IFRAN</h1>
                            <p class="text-xs text-gray-600">École Supérieure</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contenu du formulaire centré -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="max-w-md w-full space-y-8">
                <!-- Titre de connexion -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Connectez-vous</h2>
                    <p class="text-gray-600">Accédez à votre espace personnel</p>
                </div>

                <!-- Messages d'erreur -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Messages d'erreur personnalisés -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <!-- Formulaire -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                                   placeholder="votre@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi + Mot de passe oublié -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="remember"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-blue-600 hover:text-blue-500 hover:underline">
                                Vous avez oublié votre mot de passe?
                            </a>
                        @endif
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Côté droit - Image principale -->
    <div class="hidden md:flex md:w-1/2 gradient-bg items-center justify-center relative overflow-hidden">
        @if(file_exists(public_path('images/image_ifran.jpg')))
            <img src="{{ asset('images/image_ifran.jpg') }}" alt="Étudiants IFRAN" class="w-full h-full object-cover">
        @else
            <!-- Image de fallback avec illustration -->
            {{-- <div class="relative w-full h-full flex items-center justify-center">
                <!-- Overlay avec contenu -->
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative z-10 text-center text-white px-8">
                    <!-- Illustration principale -->
                    <div class="mb-8">
                        <div class="w-80 h-80 mx-auto bg-white bg-opacity-10 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <div class="text-center">
                                <i class="fas fa-users text-6xl text-white mb-4"></i>
                                <h3 class="text-2xl font-bold mb-2">Bienvenue à IFRAN</h3>
                                <p class="text-white text-opacity-90">Votre plateforme d'apprentissage digitale</p>
                            </div>
                        </div>
                    </div>

                    <!-- Icônes flottantes -->
                    <div class="grid grid-cols-5 gap-4 mt-8">
                        <div class="animate-bounce delay-100">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                        </div>
                        <div class="animate-bounce delay-200">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-comments text-white"></i>
                            </div>
                        </div>
                        <div class="animate-bounce delay-300">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                        </div>
                        <div class="animate-bounce delay-400">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-code text-white"></i>
                            </div>
                        </div>
                        <div class="animate-bounce delay-500">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-laptop-code text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Éléments décoratifs -->
                <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
                <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-10 rounded-full animate-pulse delay-300"></div>
                <div class="absolute top-1/2 left-0 w-12 h-12 bg-white bg-opacity-10 rounded-full animate-bounce"></div>
                <div class="absolute bottom-1/4 right-0 w-14 h-14 bg-white bg-opacity-10 rounded-full animate-bounce delay-200"></div>
            </div> --}}
        @endif
    </div>
</body>
</html>
