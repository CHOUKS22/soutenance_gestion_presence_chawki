<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-auto bg-white border-r border-gray-200 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logos/ifran.jpeg') }}" alt="Logo" class="w-20 h-20 object-cover rounded">
                </div>
            </div>
            <div class="p-6 flex-1">
                <nav class="space-y-4">
                    <a href="{{ route('coordinateur.dashboard') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('coordinateur.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-tachometer-alt mr-4"></i>
                        <span class="text-base">Dashboard</span>
                    </a>
                    <a href="{{ route('seances.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('seances.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-calendar-alt mr-4"></i>
                        <span class="text-base">Séances</span>
                    </a>
                    <a href="{{ route('etudiants-classes.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('etudiants-classes.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-user-plus mr-4"></i>
                        <span class="text-base">Inscriptions Étudiants</span>
                    </a>
                    <a href="{{ route('classes.classe') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('classes.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-chalkboard-teacher mr-4"></i>
                        <span class="text-base">Mes Classes</span>
                    </a>
                    <a href="{{ route('emploi.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('emploi.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-clock mr-4"></i>
                        <span class="text-base">Emploi du temps</span>
                    </a>
                    <a href="{{ route('justifications.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('justifications.*') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-file-signature mr-4"></i>
                        <span class="text-base">Justifier absence</span>
                    </a>
                    <a href="{{ route('coordinateur.presences.statistiques') }}"
                        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition {{ request()->routeIs('coordinateur.presences.statistiques') ? 'bg-gray-100 font-semibold' : '' }}">
                        <i class="fas fa-chart-bar mr-4"></i>
                        <span class="text-base">Statistiques</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <button id="toggleSidebar" class="md:hidden text-gray-700 focus:outline-none">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-600 mt-1">@yield('subtitle', 'Bienvenue dans votre espace coordinateur')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-calendar-day mr-2"></i>{{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        </div>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                    @if (auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-white text-sm"></i>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-700 hidden md:block">{{ Auth::user()->prenom ?? 'Utilisateur' }}</span>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </button>
                            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
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
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
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

    <!-- Scripts -->
    <script>
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        userMenuButton?.addEventListener('click', e => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', e => {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });

        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleSidebar?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !toggleSidebar.contains(e.target) && window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
