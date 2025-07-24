@extends('layouts.coordinateur')

@section('title', 'Dashboard Coordinateur')
@section('subtitle', 'Vue d\'ensemble de vos classes et activités')

@section('content')
<div class="space-y-6">
    <!-- Message informatif -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
            <div>
                <h4 class="text-sm font-medium text-blue-800">Données filtrées</h4>
                <p class="text-sm text-blue-600">Les statistiques ci-dessous concernent uniquement les classes que vous coordonnez.</p>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        @foreach ([
            ['label' => 'Mes Matières', 'icon' => 'fa-book', 'bg' => 'blue', 'value' => $totalMatieres],
            ['label' => 'Mes Séances', 'icon' => 'fa-calendar-day', 'bg' => 'green', 'value' => $totalSeances],
            ['label' => 'Mes Classes', 'icon' => 'fa-users', 'bg' => 'purple', 'value' => $totalClasses],
            ['label' => 'Mes Étudiants', 'icon' => 'fa-graduation-cap', 'bg' => 'yellow', 'value' => $totalEtudiants],
            ['label' => 'Taux de Présence', 'icon' => 'fa-chart-pie', 'bg' => 'indigo', 'value' => $tauxPresence . '%'],
        ] as $stat)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-{{ $stat['bg'] }}-100 rounded-lg flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['bg'] }}-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stat['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- <!-- Séances d'aujourd'hui et Matières récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Séances d'aujourd'hui -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Séances d'Aujourd'hui</h3>
                <p class="text-sm text-gray-600">Planning de vos séances</p>
            </div>
            <div class="p-6">
                @forelse($seancesAujourdhui->take(3) as $seance)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $seance->matiere->nom ?? 'Matière inconnue' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $seance->date_debut ? \Carbon\Carbon::parse($seance->date_debut)->format('H:i') : '00:00' }} -
                                {{ $seance->date_fin ? \Carbon\Carbon::parse($seance->date_fin)->format('H:i') : '00:00' }} |
                                {{ $seance->classe->nom ?? 'Classe inconnue' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucune séance programmée aujourd'hui</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Matières récentes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Matières Récentes</h3>
                <p class="text-sm text-gray-600">Matières récemment créées</p>
            </div>
            <div class="p-6">
                @forelse($matieresRecentes->take(3) as $matiere)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-green-600"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $matiere->nom }}</p>
                            <p class="text-xs text-gray-500">Créée le {{ $matiere->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-book text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Aucune matière créée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div> --}}

    <!-- Ligne supplémentaire -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Présences Récentes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Présences Récentes</h3>
                <p class="text-sm text-gray-600">Dernières prises de présence</p>
            </div>
            <div class="p-6 text-center py-8">
                <i class="fas fa-check-circle text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Aucune présence enregistrée</p>
            </div>
        </div>


        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Actions Rapides</h3>
                <p class="text-sm text-gray-600">Raccourcis fréquents</p>
            </div>
            <div class="p-6 space-y-3 text-center">
                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Créer une Séance
                </button>
                <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-book mr-2"></i>Ajouter une Matière
                </button>
                <button class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    <i class="fas fa-calendar-alt mr-2"></i>Gérer les Séances
                </button>
                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-list mr-2"></i>Gérer les Matières
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
