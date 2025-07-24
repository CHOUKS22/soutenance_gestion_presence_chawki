@extends('layouts.coordinateur')

@section('title', 'Séances d\'Aujourd\'hui')
{{-- @section('subtitle', 'Planning du {{ \Carbon\Carbon::now()->format(\'d/m/Y\') }}') --}}

@section('content')
<div class="space-y-6">
    <!-- Actions principales -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">
                <i class="fas fa-calendar-day mr-2"></i>
                <span class="font-medium">{{ $seances->count() }} séances aujourd'hui</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-clock mr-1"></i>
                {{ \Carbon\Carbon::now()->format('H:i') }}
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('gestion-seances.create') }}"
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Nouvelle séance</span>
            </a>
            <a href="{{ route('gestion-seances.index') }}"
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2 shadow-lg">
                <i class="fas fa-list"></i>
                <span>Toutes les séances</span>
            </a>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('seances.aujourdhui') }}"
               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium border-2 border-blue-300">
                <i class="fas fa-calendar-day mr-2"></i>Aujourd'hui
            </a>
            <a href="{{ route('seances.cette-semaine') }}"
               class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                <i class="fas fa-calendar-week mr-2"></i>Cette semaine
            </a>
            <a href="{{ route('seances.prochaines') }}"
               class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                <i class="fas fa-clock mr-2"></i>Prochaines
            </a>
            <a href="{{ route('seances.historique') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-history mr-2"></i>Historique
            </a>
        </div>
    </div>

    @if($seances->count() > 0)
        <!-- Vue chronologique -->
        <div class="space-y-4">
            @php
                $currentHour = null;
                $now = \Carbon\Carbon::now();
            @endphp

            @foreach($seances->sortBy('date_debut') as $seance)
                @php
                    $dateDebut = \Carbon\Carbon::parse($seance->date_debut);
                    $dateFin = \Carbon\Carbon::parse($seance->date_fin);
                    $hour = $dateDebut->format('H:00');
                    $isCurrently = $now->between($dateDebut, $dateFin);
                    $isUpcoming = $dateDebut->isFuture();
                    $isPassed = $dateFin->isPast();
                @endphp

                @if($currentHour !== $hour)
                    @php $currentHour = $hour; @endphp
                    <div class="flex items-center my-6">
                        <h3 class="text-lg font-semibold text-gray-800 mr-4">{{ $hour }}</h3>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4
                    {{ $isCurrently ? 'border-green-500 bg-green-50' :
                       ($isUpcoming ? 'border-blue-500' : 'border-gray-300') }}">

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex flex-col">
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ $seance->matiere->nom ?? 'Matière non définie' }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $seance->classe->nom ?? 'Classe non définie' }}
                                    </p>
                                </div>

                                @if($isCurrently)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-play mr-1"></i>En cours
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-clock mr-1"></i>À venir
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-check mr-1"></i>Terminée
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $dateDebut->format('H:i') }} - {{ $dateFin->format('H:i') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $dateDebut->diffInMinutes($dateFin) }} minutes
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Professeur</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $seance->professeur->user->prenom ?? 'N/A' }} {{ $seance->professeur->user->nom ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-laptop text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Type</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $seance->typeSeance->nom ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-info-circle text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Statut</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $seance->statutSeance->libelle ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                @if($isCurrently)
                                    <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-user-check mr-1"></i>Prendre présence
                                    </button>
                                @elseif($isUpcoming)
                                    <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-bell mr-1"></i>Rappel
                                    </button>
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('gestion-seances.show', $seance) }}"
                                   class="p-2 text-blue-600 hover:text-blue-900 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('gestion-seances.edit', $seance) }}"
                                   class="p-2 text-yellow-600 hover:text-yellow-900 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('gestion-seances.destroy', $seance) }}"
                                      class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Aucune séance aujourd'hui -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="text-center py-12">
                <i class="fas fa-calendar-day text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune séance aujourd'hui</h3>
                <p class="text-gray-500 mb-6">Profitez de cette journée libre ou planifiez une nouvelle séance.</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('gestion-seances.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Planifier une séance
                    </a>
                    <a href="{{ route('seances.cette-semaine') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-calendar-week mr-2"></i>
                        Voir cette semaine
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection
