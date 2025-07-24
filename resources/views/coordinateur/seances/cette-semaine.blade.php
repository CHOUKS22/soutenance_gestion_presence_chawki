@extends('layouts.coordinateur')

{{-- @section('title', 'Séances de Cette Semaine')
@section('subtitle', 'Planning du {{ \Carbon\Carbon::now()->startOfWeek()->format(\'d/m/Y\') }} au {{ \Carbon\Carbon::now()->endOfWeek()->format(\'d/m/Y\') }}') --}}

@section('content')
<div class="space-y-6">
    <!-- Actions principales -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center text-purple-600 bg-purple-50 px-4 py-2 rounded-lg">
                <i class="fas fa-calendar-week mr-2"></i>
                <span class="font-medium">{{ $seances->total() }} séances cette semaine</span>
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
               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                <i class="fas fa-calendar-day mr-2"></i>Aujourd'hui
            </a>
            <a href="{{ route('seances.cette-semaine') }}"
               class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors font-medium border-2 border-purple-300">
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
        <!-- Vue par jour de la semaine -->
        {{-- <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
            @php
                $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
                $seancesByDay = $seances->groupBy(function($seance) {
                    return \Carbon\Carbon::parse($seance->date_debut)->format('Y-m-d');
                });
                $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            @endphp

            @for($i = 0; $i < 7; $i++)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    $dayKey = $currentDay->format('Y-m-d');
                    $daySeances = $seancesByDay->get($dayKey, collect());
                    $isToday = $currentDay->isToday();
                    $isWeekend = $currentDay->isWeekend();
                @endphp

                <div class="bg-white rounded-xl shadow-md overflow-hidden {{ $isToday ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="p-4 {{ $isToday ? 'bg-blue-50' : ($isWeekend ? 'bg-gray-50' : 'bg-white') }} border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">{{ $daysOfWeek[$i] }}</h3>
                        <p class="text-sm text-gray-600">{{ $currentDay->format('d/m') }}</p>
                        @if($isToday)
                            <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Aujourd'hui</span>
                        @endif
                    </div>

                    <div class="p-4 min-h-[200px]">
                        @if($daySeances->count() > 0)
                            <div class="space-y-2">
                                @foreach($daySeances->sortBy('date_debut') as $seance)
                                    @php
                                        $dateDebut = \Carbon\Carbon::parse($seance->date_debut);
                                        $dateFin = \Carbon\Carbon::parse($seance->date_fin);
                                        $now = \Carbon\Carbon::now();
                                        $isCurrently = $now->between($dateDebut, $dateFin);
                                        $isUpcoming = $dateDebut->isFuture();
                                        $isPassed = $dateFin->isPast();
                                    @endphp

                                    <div class="p-3 rounded-lg border-l-4
                                        {{ $isCurrently ? 'border-green-500 bg-green-50' :
                                           ($isUpcoming ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50') }}
                                        hover:shadow-md transition-shadow cursor-pointer"
                                        onclick="window.location='{{ route('gestion-seances.show', $seance) }}'">

                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-sm text-gray-900 truncate">
                                                {{ $seance->matiere->nom ?? 'Matière' }}
                                            </h4>
                                            @if($isCurrently)
                                                <span class="px-1 py-0.5 bg-green-100 text-green-800 text-xs rounded">En cours</span>
                                            @elseif($isUpcoming)
                                                <span class="px-1 py-0.5 bg-blue-100 text-blue-800 text-xs rounded">À venir</span>
                                            @else
                                                <span class="px-1 py-0.5 bg-gray-100 text-gray-800 text-xs rounded">Fini</span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-gray-600 mb-1">
                                            {{ $seance->classe->nom ?? 'Classe non définie' }}
                                        </p>

                                        <p class="text-xs font-medium text-gray-900">
                                            {{ $dateDebut->format('H:i') }} - {{ $dateFin->format('H:i') }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $seance->professeur->user->prenom ?? 'Prof' }} {{ $seance->professeur->user->nom ?? '' }}
                                        </p>

                                        <div class="flex justify-between items-center mt-2">
                                            <span class="px-1 py-0.5 bg-purple-100 text-purple-800 text-xs rounded">
                                                {{ $seance->typeSeance->nom ?? 'Type' }}
                                            </span>

                                            <div class="flex space-x-1">
                                                <a href="{{ route('gestion-seances.show', $seance) }}"
                                                   class="text-blue-600 hover:text-blue-900 text-xs"
                                                   onclick="event.stopPropagation()">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('gestion-seances.edit', $seance) }}"
                                                   class="text-yellow-600 hover:text-yellow-900 text-xs"
                                                   onclick="event.stopPropagation()">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-calendar text-2xl text-gray-300 mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune séance</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endfor
        </div> --}}

        <!-- Vue liste détaillée -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Planning détaillé</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jour et Heure
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Classe
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Matière
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Professeur
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($seances as $seance)
                            @php
                                $dateDebut = \Carbon\Carbon::parse($seance->date_debut);
                                $dateFin = \Carbon\Carbon::parse($seance->date_fin);
                                $now = \Carbon\Carbon::now();
                                $isCurrently = $now->between($dateDebut, $dateFin);
                                $isToday = $dateDebut->isToday();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $isCurrently ? 'bg-green-50 border-l-4 border-green-500' : ($isToday ? 'bg-blue-50' : '') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $dateDebut->format('D d/m') }}
                                            @if($isToday)
                                                <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Aujourd'hui</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $dateDebut->format('H:i') }} - {{ $dateFin->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $seance->classe->nom ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $seance->matiere->nom ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm text-gray-900">
                                            {{ $seance->professeur->user->prenom ?? 'N/A' }} {{ $seance->professeur->user->nom ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                        {{ $seance->typeSeance->nom ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($isCurrently)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            En cours
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ $seance->statutSeance->libelle ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('gestion-seances.show', $seance) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('gestion-seances.edit', $seance) }}"
                                       class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('gestion-seances.destroy', $seance) }}"
                                          class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($seances->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $seances->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Aucune séance cette semaine -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="text-center py-12">
                <i class="fas fa-calendar-week text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune séance cette semaine</h3>
                <p class="text-gray-500 mb-6">Semaine libre ! Vous pouvez planifier de nouvelles séances.</p>
                <a href="{{ route('gestion-seances.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Planifier des séances
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
