@extends('layouts.coordinateur')

@section('title', 'Détails de la Séance')
@section('subtitle', 'Gestion des présences et informations de la séance')

@section('content')
@if(!isset($seance) || !$seance)
    <div class="alert alert-danger">
        <h4>Erreur</h4>
        <p>La séance demandée n'a pas été trouvée.</p>
        <a href="{{ route('gestion-seances.index') }}" class="btn btn-primary">Retour à la liste</a>
    </div>
@else
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion-seances.index') }}"
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Gestion des Présences</h1>
                    <p class="text-gray-600">{{ $seance->matiere->nom ?? 'N/A' }} - {{ $seance->classe->nom ?? 'N/A' }} - {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('gestion-seances.edit', $seance) }}"
                   class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
            </div>
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

        <!-- Actions en lot pour les présences -->
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
        </div>

        <!-- Liste des étudiants pour les présences -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-users mr-3 text-blue-600"></i>
                Liste de Présence
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $etudiants->count() }} étudiants)</span>
            </h2>

            @if($etudiants->count() > 0)
                <!-- Table des étudiants -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Étudiant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut Actuel
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($etudiants as $index => $etudiant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $etudiant->user->nom ?? 'N/A' }} {{ $etudiant->user->prenom ?? '' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $etudiant->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                            {{ $etudiant->user->email ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($etudiant->statut_presence === 'Présent')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>Présent
                                            </span>
                                        @elseif($etudiant->statut_presence === 'En retard')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>En retard
                                            </span>
                                        @elseif($etudiant->statut_presence === 'Absent')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                                <i class="fas fa-times-circle mr-1"></i>Absent
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold text-gray-500 rounded-full">
                                                <i class="fas fa-question-circle mr-1"></i>Non défini
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center space-x-1">
                                            <!-- Formulaire pour Présent -->
                                            <form method="POST" action="{{ route('presence.present') }}" class="inline">
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
                                            <form method="POST" action="{{ route('presence.retard') }}" class="inline">
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
                                            <form method="POST" action="{{ route('presence.absent') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
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
                        Il n'y a aucun étudiant inscrit dans la classe <strong>{{ $seance->classe->nom ?? 'N/A' }}</strong>
                        pour l'année académique active.
                    </p>
                </div>
            @endif
        </div>
        <!-- Détails de la séance -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-info-circle mr-3 text-purple-600"></i>
                Détails de la Séance
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations principales -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Informations Générales</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Classe :</span>
                            <span class="font-medium">{{ $seance->classe->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Matière :</span>
                            <span class="font-medium">{{ $seance->matiere->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Professeur :</span>
                            <span class="font-medium">{{ $seance->professeur->user->prenom ?? 'N/A' }} {{ $seance->professeur->user->nom ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type :</span>
                            <span class="font-medium">{{ $seance->typeSeance->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut :</span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ $seance->statutSeance->libelle ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Semestre :</span>
                            <span class="font-medium">{{ $seance->semestre->libelle ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informations temporelles -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Planning</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date :</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Heure de début :</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Heure de fin :</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Durée :</span>
                            @php
                                $debut = \Carbon\Carbon::parse($seance->date_debut);
                                $fin = \Carbon\Carbon::parse($seance->date_fin);
                                $duree = $debut->diff($fin);
                            @endphp
                            <span class="font-medium">{{ $duree->h }}h {{ $duree->i }}min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">État :</span>
                            @php
                                $now = \Carbon\Carbon::now();
                                $debut = \Carbon\Carbon::parse($seance->date_debut);
                                $fin = \Carbon\Carbon::parse($seance->date_fin);
                            @endphp
                            @if($now->lt($debut))
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    <i class="fas fa-clock mr-1"></i>À venir
                                </span>
                            @elseif($now->between($debut, $fin))
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    <i class="fas fa-play mr-1"></i>En cours
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    <i class="fas fa-check mr-1"></i>Terminée
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('gestion-seances.edit', $seance) }}"
                       class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i>Modifier la séance
                    </a>
                    <button onclick="window.print()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                    <a href="{{ route('gestion-seances.index') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                        <i class="fas fa-list mr-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function marquerTousPresents() {
    if (confirm('Êtes-vous sûr de vouloir marquer tous les étudiants comme présents ?')) {
        document.querySelectorAll('form[action="{{ route("presence.present") }}"] button').forEach(btn => {
            btn.click();
        });
    }
}

function marquerTousAbsents() {
    if (confirm('Êtes-vous sûr de vouloir marquer tous les étudiants comme absents ?')) {
        document.querySelectorAll('form[action="{{ route("presence.absent") }}"] button').forEach(btn => {
            btn.click();
        });
    }
}

function reinitialiserTous() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes les présences ?')) {
        window.location.reload();
    }
}
</script>

@endif
@endsection
