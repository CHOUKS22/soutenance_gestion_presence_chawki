@extends('layouts.coordinateur')

@section('title', 'Gestion des Séances')
@section('subtitle', 'Organisation et suivi de toutes vos séances')

@section('content')
    <div class="space-y-6">
        <!-- Actions principales -->
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span class="font-medium">{{ $seances->total() }} séances au total</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('seances.create') }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Séance</span>
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Séances</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $seances->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Prochaines</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $seances->filter(fn($s) => \Carbon\Carbon::parse($s->date_debut)->isFuture())->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Aujourd'hui</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $seances->filter(fn($s) => \Carbon\Carbon::parse($s->date_debut)->isToday())->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-history text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Terminées</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $seances->filter(fn($s) => \Carbon\Carbon::parse($s->date_fin)->isPast())->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Seances -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
            @if ($seances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4">Date & Heure</th>
                                <th class="px-6 py-4">Classe</th>
                                <th class="px-6 py-4">Matière</th>
                                <th class="px-6 py-4">Professeur</th>
                                <th class="px-6 py-4">Type</th>
                                <th class="px-6 py-4">Statut</th>
                                <th class="px-6 py-4 text-right">Présences</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($seances as $seance)
                                @php
                                    $dateDebut = \Carbon\Carbon::parse($seance->date_debut);
                                    $dateFin = \Carbon\Carbon::parse($seance->date_fin);
                                    $isToday = $dateDebut->isToday();
                                    $hasPresencesOrAbsences =
                                        $seance->presences->isNotEmpty() || $seance->absences->isNotEmpty();
                                @endphp
                                <tr class="{{ $isToday ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4">
                                        <div>
                                            <span class="font-medium">{{ $dateDebut->format('d/m/Y') }}</span>
                                            <span class="block text-xs text-gray-500">{{ $dateDebut->format('H:i') }} -
                                                {{ $dateFin->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $seance->anneeClasse->anneeAcademique->libelle ?? '' }} -
                                        {{ $seance->anneeClasse->classe->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">{{ $seance->matiere->nom ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $seance->professeur->user->prenom ?? '' }}
                                        {{ $seance->professeur->user->nom ?? '' }}</td>
                                    <td class="px-6 py-4">{{ $seance->typeSeance->nom ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statut = $seance->statutSeance->libelle ?? '-';
                                        @endphp

                                        <span
                                            class="inline-flex items-center gap-2
        {{ in_array($statut, ['Reportée', 'Annulée']) ? 'text-red-600 font-semibold' : '' }}">
                                            @if (in_array($statut, ['Reportée', 'Annulée']))
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            @endif
                                            {{ $statut }}
                                        </span>

                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('seances.presences', $seance) }}"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 text-sm font-semibold flex items-center gap-1">
                                                <i class="fas fa-user-check"></i> Marquer
                                            </a>
                                            <span
                                                class="w-3 h-3 rounded-full {{ $hasPresencesOrAbsences ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('seances.show', $seance) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('seances.edit', $seance) }}"
                                            class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('seances.destroy', $seance) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Supprimer cette séance ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="p-4 border-t">{{ $seances->links() }}</div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-alt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-lg font-medium text-gray-900 mb-2">Aucune séance trouvée</p>
                    <a href="{{ route('seances.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Créer une séance
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
