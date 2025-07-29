@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('matieres.index') }}"
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Détails de la Matière</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('matieres.edit', $matiere->id) }}"
                   class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <form method="POST" action="{{ route('matieres.destroy', $matiere->id) }}"
                      class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2 shadow-lg">
                        <i class="fas fa-trash"></i>
                        <span>Supprimer</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                    Informations Générales
                </h2>

                <div class="space-y-6">
                    <!-- Nom de la matière -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la Matière</label>
                        <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $matiere->nom }}</h3>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            @if($matiere->description)
                                <p class="text-gray-900 leading-relaxed">{{ $matiere->description }}</p>
                            @else
                                <p class="text-gray-500 italic">Aucune description disponible</p>
                            @endif
                        </div>
                    </div>

                    <!-- Code matière (si disponible) -->
                    @if($matiere->code ?? null)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code Matière</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-lg font-mono text-gray-900">{{ $matiere->code }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Coefficient (si disponible) -->
                    @if($matiere->coefficient ?? null)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Coefficient</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-lg font-medium text-gray-900">{{ $matiere->coefficient }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-green-600"></i>
                    Statistiques
                </h3>

                <div class="space-y-4">
                    <!-- Nombre de séances -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Séances</p>
                                <p class="text-2xl font-bold text-blue-900">
                                    {{ $matiere->seances ? $matiere->seances->count() : 0 }}
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre de professeurs -->
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Professeurs</p>
                                <p class="text-2xl font-bold text-green-900">
                                    {{ $matiere->seances ? $matiere->seances->pluck('professeur_id')->unique()->count() : 0 }}
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-chalkboard-teacher text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Dernière séance -->
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Dernière séance</p>
                                <p class="text-sm font-medium text-purple-900">
                                    @if($matiere->seances && $matiere->seances->sortByDesc('date_debut')->first())
                                        {{ \Carbon\Carbon::parse($matiere->seances->sortByDesc('date_debut')->first()->date_debut)->format('d/m/Y') }}
                                    @else
                                        Aucune séance
                                    @endif
                                </p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Séances récentes -->
        @if($matiere->seances && $matiere->seances->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <p class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt mr-3 text-indigo-600"></i>
                    Séances Récentes
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Professeur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($matiere->seances->sortByDesc('date_debut')->take(5) as $seance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $seance->classe->nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $seance->professeur->user->nom ?? 'N/A' }} {{ $seance->professeur->user->prenom ?? '' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $seance->type_seance->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            {{ $seance->statut_seance->nom ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($matiere->seances->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('seances.index') }}?matiere={{ $matiere->id }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir toutes les séances →
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Informations d'audit -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <p class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-history mr-3 text-purple-600"></i>
                Informations d'Audit
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de Création</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-900">{{ $matiere->created_at->format('d/m/Y à H:i') }}</span>
                        <span class="text-sm text-gray-500 block">{{ $matiere->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dernière Modification</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-900">{{ $matiere->updated_at->format('d/m/Y à H:i') }}</span>
                        <span class="text-sm text-gray-500 block">{{ $matiere->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
