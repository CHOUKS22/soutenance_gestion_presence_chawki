@extends('layouts.coordinateur')

@section('title', 'Détails de la Séance')
@section('subtitle', 'Informations de la séance')

@section('content')
    @if (!isset($seance) || !$seance)
        <div class="alert alert-danger">
            <h4>Erreur</h4>
            <p>La séance demandée n'a pas été trouvée.</p>
            <a href="{{ route('seances.index') }}" class="btn btn-primary">Retour à la liste</a>
        </div>
    @else
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('seances.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Gestion des Présences</h1>
                    <p class="text-gray-600">{{ $seance->matiere->nom ?? 'N/A' }} -
                        {{ $seance->classe->nom ?? 'N/A' }} -
                        {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('seances.edit', $seance) }}"
                    class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
            </div>
        </div>
        <div class="min-h-screen bg-gray-50">
            <div class="p-6">
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
                                    <span class="font-medium">{{ $seance->professeur->user->prenom ?? 'N/A' }}
                                        {{ $seance->professeur->user->nom ?? '' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type :</span>
                                    <span class="font-medium">{{ $seance->typeSeance->nom ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Statut :</span>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
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
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Heure de début :</span>
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Heure de fin :</span>
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}</span>
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
                                    @if ($now->lt($debut))
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            <i class="fas fa-clock mr-1"></i>À venir
                                        </span>
                                    @elseif($now->between($debut, $fin))
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            <i class="fas fa-play mr-1"></i>En cours
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
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
                            <a href="{{ route('seances.edit', $seance) }}"
                                class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                                <i class="fas fa-edit mr-2"></i>Modifier la séance
                            </a>
                            {{-- <button onclick="window.print()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-print mr-2"></i>Imprimer
                            </button> --}}
                            <a href="{{ route('seances.index') }}"
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                                <i class="fas fa-list mr-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
