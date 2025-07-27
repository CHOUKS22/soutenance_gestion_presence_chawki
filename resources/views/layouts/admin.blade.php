<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - IFRAN</title>
    <!-- TailwindCSS pour la mise en forme + Font Awesome pour les icones -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Scrollbar personnalise pour un style plus propre */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation douce quand un element apparait */
        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styles de la sidebar */
        .sidebar-item {
            transition: all 0.3s ease;
            position: relative;
            min-height: 48px;
            display: flex;
            align-items: center;
        }

        .sidebar-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateX(3px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ffffff;
        }

        .sidebar-nav {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Menu lateral -->
        <aside
            class="w-full md:w-80 bg-gradient-to-b from-blue-900 to-blue-800 text-white md:fixed md:h-full shadow-2xl z-40">
            <div class="p-6">
                <!-- Logo IFRAN en haut -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/logo_ifran.png') }}" alt="Logo" width="150" height="150" />
                </div>


                <!-- Liens navigation -->
                <nav class="sidebar-nav space-y-1 pb-8">
                    <!-- Accueil -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
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
                    <a href="{{ route('users.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('users.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-users-cog mr-4 text-lg"></i>
                        <span class="font-medium">Utilisateurs</span>
                    </a>

                    <!-- Rôles -->
                    <a href="{{ route('roles.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('roles.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-tag mr-4 text-lg"></i>
                        <span class="font-medium">Rôles</span>
                    </a>

                    <!-- Étudiants -->
                    <a href="{{ route('etudiants.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('etudiants.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-graduate mr-4 text-lg"></i>
                        <span class="font-medium">Étudiants</span>
                    </a>

                    <!-- Parents -->
                    <a href="{{ route('parents.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('parents.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-friends mr-4 text-lg"></i>
                        <span class="font-medium">Parents</span>
                    </a>

                    <!-- Professeurs -->
                    <a href="{{ route('professeurs.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('professeurs.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-chalkboard-teacher mr-4 text-lg"></i>
                        <span class="font-medium">Professeurs</span>
                    </a>

                    <!-- Coordinateurs -->
                    <a href="{{ route('coordinateurs.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('coordinateurs.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-user-tie mr-4 text-lg"></i>
                        <span class="font-medium">Coordinateurs</span>
                    </a>

                    <!-- Admins -->
                    <a href="{{ route('admins.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('admins.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
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
                    <a href="{{ route('classes.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('classes.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-school mr-4 text-lg"></i>
                        <span class="font-medium">Classes</span>
                    </a>
                    <a href="{{ route('matieres.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('classes.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-book mr-3"></i>
                        <span class="font-medium">Matières</span>
                    </a>


                    <!-- Années-Classes -->
                    <a href="{{ route('annees-classes.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('annees-classes.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-link mr-4 text-lg"></i>
                        <span class="font-medium">Années-Classes</span>
                    </a>

                    <!-- Années académiques -->
                    <a href="{{ route('annees-academiques.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('annees-academiques.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-calendar-check mr-4 text-lg"></i>
                        <span class="font-medium">Années académiques</span>
                    </a>

                    <!-- Semestres -->
                    <a href="{{ route('semestres.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('semestres.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
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
                    <a href="{{route('types-seances.index')}}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('types-seances.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-tags mr-4 text-lg"></i>
                        <span class="font-medium">Types de séances</span>
                    </a>

                    <!-- Statuts de séances -->
                    <a href="{{route('statuts-seances.index')}}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('statuts-seances.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
                        <i class="fas fa-flag mr-4 text-lg"></i>
                        <span class="font-medium">Statuts de séances</span>
                    </a>

                    <!-- Statuts de présences -->
                    <a href="   {{ route('statuts-presences.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('statuts-presences.*') ? 'active' : 'text-blue-200 hover:text-white hover:bg-blue-700' }} transition-all duration-200">
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
        </aside>

        <!-- Contenu principal -->
        <div class="flex-1 md:ml-80">

            <!-- En tete -->
            <header class="bg-white shadow-sm border-b sticky top-0 z-30">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="text-lg font-semibold text-gray-700 hidden md:block">Tableau de bord</div>
                    <div class="flex items-center space-x-4">
                        {{-- <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full">
                            <i class="fas fa-bell"></i>
                        </button> --}}
                        <div class="relative">
                            <button id="userMenuButton"
                                class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100">
                                <div
                                    class="w-9 h-9 bg-blue-600 rounded-full text-white font-bold flex items-center justify-center overflow-hidden">
                                    @if (auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo"
                                            class="w-full h-full object-cover">
                                    @else
                                        {{ substr(auth()->user()->nom ?? 'A', 0, 1) }}
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down text-sm text-gray-500"></i>
                            </button>
                            <div id="userMenu"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border hidden z-50">
                                <div class="py-2">
                                    <div class="px-4 py-3 border-b">
                                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->nom }}
                                            {{ auth()->user()->prenom }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Se deconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Zone de contenu -->
            <main class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 fade-in">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 fade-in">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Script menu utilisateur -->
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
    </script>

    @yield('scripts')
</body>

</html>
