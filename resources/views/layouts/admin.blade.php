<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - IFRAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Barre de défilement personnalisée */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animations personnalisées */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Effets de survol de la barre latérale */
        .sidebar-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white !important;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ffffff;
            border-radius: 0 2px 2px 0;
        }

        /* En-têtes de section dans la barre latérale */
        .sidebar-section {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        /* Taille d'icône cohérente */
        .sidebar-item i {
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Meilleur retour à la ligne pour les éléments de menu longs */
        .sidebar-item span {
            word-break: break-word;
            line-height: 1.4;
        }

        /* Empêcher le débordement du contenu de la barre latérale */
        .sidebar-nav {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        /* Espacement des en-têtes de section */
        .sidebar-section-header {
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding: 0.5rem 1rem;
        }

        /* Effet de survol amélioré */
        .sidebar-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateX(3px);
        }

        /* Assurer un espacement et un alignement appropriés */
        .sidebar-item {
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        /* Améliorations de la barre de défilement pour la barre latérale */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Barre latérale -->
        <div class="w-80 bg-gradient-to-b from-blue-900 to-blue-800 text-white fixed h-full shadow-2xl overflow-y-auto z-40">
            <div class="p-6 pb-8">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-white rounded-xl mr-4 flex items-center justify-center shadow-lg">
                        <span class="text-blue-800 font-bold text-lg">IFRAN</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">IFRAN</h1>
                        <p class="text-blue-200 text-sm">GESTION</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav space-y-1 pb-8">
                    <!-- Accueil -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-home mr-4 text-lg"></i>
                        <span class="font-medium">Accueil</span>
                    </a>

                    <!-- Section: Gestion des Utilisateurs -->
                    <div class="sidebar-section-header">
                        <div class="flex items-center text-xs font-semibold text-blue-300 uppercase tracking-wider">
                            <i class="fas fa-users mr-2 text-sm"></i>
                            <span>Gestion des Utilisateurs</span>
                        </div>
                    </div>

                    <!-- Utilisateurs -->
                    <a href="{{ route('users.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('users.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-users-cog mr-4 text-lg"></i>
                        <span class="font-medium">Utilisateurs</span>
                    </a>

                    <!-- Rôles -->
                    <a href="{{ route('gestion-roles.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-roles.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-tag mr-4 text-lg"></i>
                        <span class="font-medium">Rôles</span>
                    </a>

                    <!-- Étudiants -->
                    <a href="{{ route('gestion-etudiants.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-etudiants.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-graduate mr-4 text-lg"></i>
                        <span class="font-medium">Étudiants</span>
                    </a>

                    <!-- Parents -->
                    <a href="{{ route('gestion-parents.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-parents.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-friends mr-4 text-lg"></i>
                        <span class="font-medium">Parents</span>
                    </a>

                    <!-- Professeurs -->
                    <a href="{{ route('gestion-professeurs.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-professeurs.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-chalkboard-teacher mr-4 text-lg"></i>
                        <span class="font-medium">Professeurs</span>
                    </a>

                    <!-- Coordinateurs -->
                    <a href="{{ route('gestion-coordinateurs.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-coordinateurs.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-tie mr-4 text-lg"></i>
                        <span class="font-medium">Coordinateurs</span>
                    </a>

                    <!-- Admins -->
                    <a href="{{ route('gestion-admins.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-admins.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-shield mr-4 text-lg"></i>
                        <span class="font-medium">Administrateurs</span>
                    </a>

                    <!-- Section: Gestion Académique -->
                    <div class="sidebar-section-header">
                        <div class="flex items-center text-xs font-semibold text-blue-300 uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2 text-sm"></i>
                            <span>Gestion Académique</span>
                        </div>
                    </div>

                    <!-- Classes -->
                    <a href="{{ route('gestion-classes.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-classes.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-school mr-4 text-lg"></i>
                        <span class="font-medium">Classes</span>
                    </a>

                    <!-- Années-Classes -->
                    <a href="{{ route('gestion-annees-classes.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-annees-classes.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-link mr-4 text-lg"></i>
                        <span class="font-medium">Années-Classes</span>
                    </a>

                    <!-- Années académiques -->
                    <a href="{{ route('gestion-annees-academiques.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-annees-academiques.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-calendar-check mr-4 text-lg"></i>
                        <span class="font-medium">Années académiques</span>
                    </a>

                    <!-- Semestres -->
                    <a href="{{ route('gestion-semestres.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-semestres.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-calendar-week mr-4 text-lg"></i>
                        <span class="font-medium">Semestres</span>
                    </a>

                    <!-- Section: Paramètres des Séances -->
                    <div class="sidebar-section-header">
                        <div class="flex items-center text-xs font-semibold text-blue-300 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2 text-sm"></i>
                            <span>Paramètres des Séances</span>
                        </div>
                    </div>

                    <!-- Types de séances -->
                    <a href="{{ route('gestion-types-seances.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-types-seances.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-tags mr-4 text-lg"></i>
                        <span class="font-medium">Types de séances</span>
                    </a>

                    <!-- Statuts de séances -->
                    <a href="{{ route('gestion-statuts-seances.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-statuts-seances.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-flag mr-4 text-lg"></i>
                        <span class="font-medium">Statuts de séances</span>
                    </a>

                    <!-- Statuts de présences -->
                    <a href="{{ route('gestion-statuts-presences.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('gestion-statuts-presences.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-check mr-4 text-lg"></i>
                        <span class="font-medium">Statuts de présences</span>
                    </a>

                    {{-- <!-- Rapports -->
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-xl text-blue-200 hover:text-white hover:bg-blue-700 transition-all duration-200">
                        <i class="fas fa-chart-bar mr-4 text-lg w-5"></i>
                        <span class="font-medium">Rapports</span>
                    </a>

                    <!-- Configuration -->
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-xl text-blue-200 hover:text-white hover:bg-blue-700 transition-all duration-200">
                        <i class="fas fa-cog mr-4 text-lg w-5"></i>
                        <span class="font-medium">Configuration</span>
                    </a> --}}
                </nav>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 ml-80 relative z-10">
            <!-- En-tête -->
            <header class="bg-white shadow-sm border-b sticky top-0 z-30">
                <div class="px-8 py-6 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher..."
                                   class="w-96 px-4 py-3 pl-12 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50">
                            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-3 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                        </button>
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-3 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold overflow-hidden">
                                    @if(auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo de profil" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(auth()->user()->nom ?? 'A', 0, 1) }}
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                            </button>

                            <!-- Menu déroulant -->
                            <div id="userMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden">
                                <div class="py-2">
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->nom ?? 'Admin' }} {{ auth()->user()->prenom ?? '' }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</p>
                                    </div>
                                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-3"></i>Mon profil
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-3"></i>Paramètres
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu de la page -->
            <main class="p-8">
                <!-- Messages de succès/erreur -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 fade-in">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 fade-in">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Gestion du menu utilisateur
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            // Fermer le menu en cliquant ailleurs
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Empêcher la fermeture du menu en cliquant à l'intérieur
            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
