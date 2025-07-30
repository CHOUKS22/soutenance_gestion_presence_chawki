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

<body class="h-screen w-screen overflow-hidden flex">

    <!-- Côté gauche - Formulaire -->
    <div class="w-full md:w-1/2 h-full flex flex-col bg-white">
        <!-- Logo -->
        <div class="p-8 pb-0">
            <div class="flex items-center">
                @if (file_exists(public_path('images/logos/ifran.jpeg')))
                    <img src="{{ asset('images/logos/ifran.jpeg') }}" alt="IFRAN Logo" class="h-28">
                @else
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">IFRAN</h1>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Formulaire centré -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="max-w-md w-full space-y-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Connectez-vous</h2>
                    <p class="text-gray-600">Accédez à votre espace personnel</p>
                </div>

                <!-- Statut session -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Erreur personnalisée -->
                @if (session('error'))
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
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus
                            class="block w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                            placeholder="votre@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Souvenir + lien -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                        {{--
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500 hover:underline">
                                Mot de passe oublié ?
                            </a>
                        @endif --}}
                    </div>

                    <!-- Bouton -->
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Côté droit - Image -->
    <div class="hidden md:flex md:w-1/2 h-full gradient-bg items-center justify-center relative overflow-hidden">
        @if (file_exists(public_path('images/image_ifran.jpg')))
            <img src="{{ asset('images/image_ifran.jpg') }}" alt="Étudiants IFRAN" class="w-full h-full object-cover">
        @else
            <div class="text-white text-center p-8">
                <h2 class="text-4xl font-bold mb-4">Bienvenue à IFRAN</h2>
                <p class="text-lg opacity-80">Votre plateforme de gestion des présences</p>
            </div>
        @endif
    </div>

</body>

</html>
