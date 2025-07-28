@extends('layouts.professeur')

@section('title', 'Détails de la liste de présence')
@section('subtitle', 'Gestion des présences')

@section('content')

    <div class="min-h-screen bg-gray-50">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('professeur.seances.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Gestion des Présences</h1>
                        <p class="text-gray-600">{{ $seance->matiere->nom ?? 'N/A' }} -
                            {{ $seance->anneeClasse->classe->nom ?? 'N/A' }} -
                            {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                {{-- <div class="flex space-x-3">
                <a href="{{ route('seances.edit', $seance) }}"
                    class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
            </div> --}}
            </div>

            <!-- Statistiques de présence -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistiques['total'] }}</div>
                    <div class="text-sm text-blue-600">Total étudiants</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $statistiques['presents'] }}</div>
                    <div class="text-sm text-green-600">Présents</div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $statistiques['retards'] }}</div>
                    <div class="text-sm text-orange-600">En retard</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $statistiques['absents'] }}</div>
                    <div class="text-sm text-red-600">Absents</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $statistiques['non_definis'] }}</div>
                    <div class="text-sm text-gray-600">Non définis</div>
                </div>
            </div>

            {{-- <!-- Actions en lot pour les présences -->
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Actions en lot :</h3>
                <div class="flex flex-wrap gap-2">
                    <button onclick="marquerTousPresents()"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-check mr-2"></i>Marquer tous présents
                    </button>
                    <button onclick="marquerTousAbsents()"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-times mr-2"></i>Marquer tous absents
                    </button>
                    <button onclick="reinitialiserTous()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-undo mr-2"></i>Réinitialiser tout
                    </button>
                </div>
            </div> --}}

            <!-- Liste des étudiants pour les présences -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    Liste de Présence
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $etudiants->count() }} étudiants)</span>
                </h2>

                @if ($etudiants->count() > 0)
                    <!-- Table des étudiants -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Étudiant
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut Actuel
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($etudiants as $index => $etudiant)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                                                    @if ($etudiant->user->photo)
                                                        <img src="{{ asset('storage/' . $etudiant->user->photo) }}"
                                                            alt="Photo de profil" class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full bg-blue-100 flex items-center justify-center">
                                                            <i class="fas fa-user text-blue-600"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $etudiant->user->nom ?? 'N/A' }}
                                                        {{ $etudiant->user->prenom ?? '' }}
                                                    </div>
                                                    {{-- <div class="text-sm text-gray-500">
                                                            ID: {{ $etudiant->id }}
                                                        </div> --}}
                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                    {{ $etudiant->user->email ?? 'N/A' }}
                                                </div>
                                            </td> --}}
                                        @php
                                            $presence = $presences[$etudiant->id] ?? null;
                                            $statut = $presence->statutPresence->libelle ?? null;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($statut === 'Présent')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                    <i class="fas fa-check-circle mr-1"></i>Présent
                                                </span>
                                            @elseif($statut === 'En retard')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full">
                                                    <i class="fas fa-clock mr-1"></i>En retard
                                                </span>
                                            @elseif($statut === '')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold text-gray-500 rounded-full">
                                                    <i class="fas fa-question-circle mr-1"></i>Non défini
                                                </span>
                                            @else
                                            <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                                    <i class="fas fa-times-circle mr-1"></i>Absent
                                                </span>

                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-1">
                                                <!-- Formulaire pour Présent -->
                                                <form method="POST" action="{{ route('professeur.presence.present') }}"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                                                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">
                                                    <button type="submit"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                        title="Marquer présent">
                                                        <i class="fas fa-check mr-1"></i>Présent
                                                    </button>
                                                </form>

                                                <!-- Formulaire pour En retard -->
                                                <form method="POST" action="{{ route('professeur.presence.retard') }}"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                                                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">
                                                    <button type="submit"
                                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                        title="Marquer en retard">
                                                        <i class="fas fa-clock mr-1"></i>Retard
                                                    </button>
                                                </form>

                                                <!-- Formulaire pour Absent -->
                                                <form method="POST" action="{{ route('professeur.presence.absent') }}"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="etudiant_id"
                                                        value="{{ $etudiant->id }}">
                                                    <input type="hidden" name="seance_id" value="{{ $seance->id }}">
                                                    <button type="submit"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors"
                                                        title="Marquer absent">
                                                        <i class="fas fa-times mr-1"></i>Absent
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Message si aucun étudiant -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun étudiant inscrit</h3>
                        <p class="text-gray-500 mb-4">
                            Il n'y a aucun étudiant inscrit dans la classe
                            <strong>{{ $seance->anneeClasse->classe->nom ?? 'N/A' }}</strong>
                            pour cette année académique.
                        </p>
                    </div>
            </div>
        </div>
    </div>
{{-- <script>
        function marquerTousPresents() {
            if (confirm('Êtes-vous sûr de vouloir marquer tous les étudiants comme présents ?')) {
                document.querySelectorAll('form[action="{{ route('presence.present') }}"] button').forEach(btn => {
                    btn.click();
                });
            }
        }

        function marquerTousAbsents() {
            if (confirm('Êtes-vous sûr de vouloir marquer tous les étudiants comme absents ?')) {
                document.querySelectorAll('form[action="{{ route('presence.absent') }}"] button').forEach(btn => {
                    btn.click();
                });
            }
        }

        function reinitialiserTous() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes les présences ?')) {
                window.location.reload();
            }
        }
</script> --}}
    @endif
@endsection
