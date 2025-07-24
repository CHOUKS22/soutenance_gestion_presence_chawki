@extends('layouts.coordinateur')

@section('title', 'Séances Prochaines')
@section('subtitle', 'Planification et organisation des séances à venir')

@section('content')
<div class="space-y-6">
    <!-- Actions principales -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                <i class="fas fa-clock mr-2"></i>
                <span class="font-medium">{{ $seances->total() }} séances à venir</span>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('gestion-seances.create') }}"
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Planifier une séance</span>
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
               class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors">
                <i class="fas fa-calendar-week mr-2"></i>Cette semaine
            </a>
            <a href="{{ route('seances.prochaines') }}"
               class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors font-medium border-2 border-green-300">
                <i class="fas fa-clock mr-2"></i>Prochaines
            </a>
            <a href="{{ route('seances.historique') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-history mr-2"></i>Historique
            </a>
        </div>
    </div>

    <!-- Liste des séances -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($seances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date et Heure
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
                                $isToday = \Carbon\Carbon::parse($seance->date_debut)->isToday();
                                $isThisWeek = \Carbon\Carbon::parse($seance->date_debut)->isCurrentWeek();
                                $dateDebut = \Carbon\Carbon::parse($seance->date_debut);
                                $dateFin = \Carbon\Carbon::parse($seance->date_fin);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $isToday ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $dateDebut->format('d/m/Y') }}
                                            @if($isToday)
                                                <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Aujourd'hui</span>
                                            @elseif($isThisWeek)
                                                <span class="ml-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Cette semaine</span>
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
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        {{ $seance->typeSeance->nom ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        {{ $seance->statutSeance->libelle ?? 'N/A' }}
                                    </span>
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
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-check text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune séance prochaine</h3>
                <p class="text-gray-500 mb-6">Toutes vos séances sont terminées ou il n'y en a pas de planifiées.</p>
                <a href="{{ route('gestion-seances.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Planifier une nouvelle séance
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
