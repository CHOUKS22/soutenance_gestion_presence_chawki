@extends('layouts.coordinateur')

@section('title', 'Espace Coordinateur')
@section('subtitle', 'Suivi centralisé des classes, séances et étudiants')

@section('content')
    <div class="space-y-10">

        <!-- En-tete -->
        <div
            class="bg-gradient-to-r from-indigo-700 to-blue-600 text-white p-8 rounded-2xl shadow-lg flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold">Bonjour {{ auth()->user()->prenom }},</h1>
                <p class="text-white/90 text-sm mt-1">Vous êtes connecté en tant que coordinateur.</p>
            </div>
            <div>
                <i class="fas fa-user-cog text-5xl opacity-60"></i>
            </div>
        </div>
        @if (!empty($etudiantsDroppes) && count($etudiantsDroppes) > 0)
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <h2 class="text-xl font-semibold text-red-600 mb-4">Étudiants Droppés (≤ 70% de présence)</h2>
                <ul class="space-y-2">
                    @foreach ($etudiantsDroppes as $drop)
                        <li class="text-gray-800">
                            <strong>{{ $drop->etudiant }}</strong> – {{ $drop->matiere }} :
                            <span class="text-red-500 font-semibold">{{ $drop->taux }}%</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @php
                $stats = [
                    // [
                    //     'label' => 'Matières',
                    //     'icon' => 'fa-book',
                    //     'color' => 'bg-blue-100',
                    //     'iconColor' => 'text-blue-600',
                    //     'count' => $totalMatieres,
                    // ],
                    [
                        'label' => 'Séances',
                        'icon' => 'fa-calendar-day',
                        'color' => 'bg-green-100',
                        'iconColor' => 'text-green-600',
                        'count' => $totalSeances,
                    ],
                    [
                        'label' => 'Classes',
                        'icon' => 'fa-users',
                        'color' => 'bg-purple-100',
                        'iconColor' => 'text-purple-600',
                        'count' => $totalClasses,
                    ],
                    [
                        'label' => 'Étudiants',
                        'icon' => 'fa-user-graduate',
                        'color' => 'bg-yellow-100',
                        'iconColor' => 'text-yellow-600',
                        'count' => $totalEtudiants,
                    ],
                    [
                        'label' => 'Présence',
                        'icon' => 'fa-chart-line',
                        'color' => 'bg-indigo-100',
                        'iconColor' => 'text-indigo-600',
                        'count' => $tauxPresence . '%',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md transition">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full {{ $stat['color'] }} flex items-center justify-center">
                        <i class="fas {{ $stat['icon'] }} {{ $stat['iconColor'] }} text-lg"></i>
                    </div>
                    <p class="text-gray-500 text-sm">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['count'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Actions rapides -->
            <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Actions rapides</h3>
                @php
                    $actions = [
                        [
                            'label' => 'Créer une séance',
                            'icon' => 'fa-plus',
                            'route' => route('seances.create'),
                            'color' => 'bg-blue-600',
                        ],
                        [
                            'label' => 'Gérer les emplois du temps',
                            'icon' => 'fa-book',
                            'route' => route('emploi.index'),
                            'color' => 'bg-green-600',
                        ],
                        [
                            'label' => 'Liste des classes',
                            'icon' => 'fa-user-graduate',
                            'route' => route('classes.classe'),
                            'color' => 'bg-purple-600',
                        ],
                        [
                            'label' => 'Voir les séances',
                            'icon' => 'fa-calendar-alt',
                            'route' => route('seances.index'),
                            'color' => 'bg-indigo-600',
                        ],
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($actions as $action)
                        <a href="{{ $action['route'] }}"
                            class="group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div
                                class="w-12 h-12 rounded-full {{ $action['color'] }} text-white flex items-center justify-center mb-2">
                                <i class="fas {{ $action['icon'] }}"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700 text-center group-hover:underline">
                                {{ $action['label'] }}
                            </p>
                        </a>
                    @endforeach
                </div>

            </div>
            <!-- Séances du jour -->
            <div class="bg-white rounded-2xl shadow p-6 col-span-2">
                <p class="text-lg font-bold text-gray-800 mb-4">Séances du jour</p>
                @forelse($seancesAujourdhui as $seance)
                    <div class="border rounded-lg p-4 mb-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center mb-1">
                            <span
                                class="font-semibold text-gray-700">{{ $seance->matiere->nom ?? 'Matière inconnue' }}</span>
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $seance->anneeClasse->classe->nom ?? 'Classe inconnue' }} |
                            {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-12">
                        <i class="fas fa-calendar-times text-4xl mb-3"></i><br>
                        Aucune séance prévue aujourd'hui
                    </div>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6 max-h-[300px] overflow-auto">
            <p class="text-lg font-bold text-gray-800 mb-4">Taux de présence par classe</p>
            <canvas id="presenceChart" class="w-full max-h-52"></canvas>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('presenceChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels !!},
                datasets: [{
                    label: 'Taux de présence (%)',
                    data: {!! $chartData !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: value => value + '%',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
