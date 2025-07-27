<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind / App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
            <div class="p-6 border-b">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logos/ifran.jpeg') }}" alt="Logo"
                        class="w-20 h-20 object-cover rounded">
                </div>
            </div>
            <div class="p-6 flex-1">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Navigation</h2>
                <nav class="space-y-4"> <!-- Espace vertical augmenté -->
                    <a href="{{ route('etudiant.dashboard') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('coordinateur.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-tachometer-alt mr-4"></i> <!-- icône décalé -->
                        <span class="text-base">Dashboard</span>
                    </a>

                    {{-- <a href="{{ route('seances.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-calendar-alt mr-4"></i>
                        <span class="text-base">Séances</span>
                    </a>

                    <a href="{{ route('etudiants-classes.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-users mr-4"></i>
                        <span class="text-base">Inscriptions Étudiants</span>
                    </a>

                    <a href="{{ route('classes.classe') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-users mr-4"></i>
                        <span class="text-base">Mes Classes</span>
                    </a>

                    <a href="{{ route('coordinateur.etudiants') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-user-graduate mr-4"></i>
                        <span class="text-base">Mes Étudiants</span>
                    </a>

                    <a href="{{ route('emploi.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-user-graduate mr-4"></i>
                        <span class="text-base">Emploie du temps</span>
                    </a>
                     <a href="{{ route('justifications.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-user-graduate mr-4"></i>
                        <span class="text-base">Justifier absence</span>
                    </a> --}}
                </nav>
            </div>
        </aside>
        <!-- Contenu principal -->
        <main class="flex-1 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-600 mt-1">@yield('subtitle', 'Bienvenue dans votre espace etudiant')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button
                                class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Date actuelle -->
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar-day mr-2"></i>
                                {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                            </div>

                            <!-- Menu utilisateur -->
                            <div class="relative">
                                <button id="userMenuButton"
                                    class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                        @if (auth()->user()->photo)
                                            <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                                alt="Photo de profil" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <i class="fas fa-user text-white text-sm"></i>
                                        @endif
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 hidden md:block">{{ Auth::user()->prenom ?? 'Utilisateur' }}</span>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>

                                <!-- Menu déroulant -->
                                <div id="userMenu"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                                    <div class="py-2">
                                        <div class="px-4 py-3 border-b border-gray-200">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ auth()->user()->nom ?? 'Nom' }} {{ auth()->user()->prenom ?? '' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                                        </div>
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt mr-3"></i>Se déconnecter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu principal -->
            <div class="p-6 overflow-y-auto" style="height: calc(100vh - 80px);">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Menu utilisateur JS -->
    <script>
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    </script>
</body>

</html>
