@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Titre de la page -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Détails de l'Étudiant</h1>
            <div class="flex space-x-2">
                <a href="{{ route('etudiants.edit', $etudiant) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('etudiants.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>

        <!-- Zone d'informations -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <!-- Photo + Infos principales -->
                <div class="flex items-center space-x-6 mb-6">
                    <div>
                        @if ($etudiant->user->photo)
                            <img src="{{ asset('storage/' . $etudiant->user->photo) }}" alt="photo" class="h-24 w-24 rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-gray-200">
                                <i class="fas fa-user text-gray-500 text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                        </h2>
                        <div class="flex space-x-4 text-sm text-gray-600">
                            <span class="flex items-center"><i class="fas fa-envelope mr-2"></i>{{ $etudiant->user->email }}</span>
                            <span class="flex items-center"><i class="fas fa-user-graduate mr-2"></i>Étudiant</span>
                        </div>
                    </div>
                </div>

                <!-- Détails par section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Infos personnelles -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i> Informations personnelles
                        </h3>
                        <div class="space-y-2">
                            <p><span class="text-sm text-gray-600">Nom : </span><span class="text-sm text-gray-900">{{ $etudiant->user->nom }}</span></p>
                            <p><span class="text-sm text-gray-600">Prénom : </span><span class="text-sm text-gray-900">{{ $etudiant->user->prenom }}</span></p>
                            <p><span class="text-sm text-gray-600">Date de naissance : </span>
                                <span class="text-sm text-gray-900">
                                    {{ $etudiant->date_naissance ? $etudiant->date_naissance->format('d/m/Y') : 'Non défini' }}
                                </span>
                            </p>
                            <p><span class="text-sm text-gray-600">Lieu de naissance : </span><span class="text-sm text-gray-900">{{ $etudiant->lieu_naissance ?: 'Non défini' }}</span></p>
                        </div>
                    </div>

                    <!-- Infos de contact -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-address-book mr-2"></i> Informations de contact
                        </h3>
                        <div class="space-y-2">
                            <p><span class="text-sm text-gray-600">Email : </span><span class="text-sm text-gray-900">{{ $etudiant->user->email }}</span></p>
                            <p><span class="text-sm text-gray-600">Téléphone : </span><span class="text-sm text-gray-900">{{ $etudiant->telephone ?: 'Non défini' }}</span></p>
                        </div>
                    </div>

                    <!-- Infos système -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-cog mr-2"></i> Informations système
                        </h3>
                        <div class="space-y-2">
                            <p><span class="text-sm text-gray-600">Rôle : </span><span class="text-sm text-gray-900">{{ $etudiant->user->role->nom ?? 'Non défini' }}</span></p>
                            <p><span class="text-sm text-gray-600">Créé le : </span><span class="text-sm text-gray-900">{{ $etudiant->created_at->format('d/m/Y à H:i') }}</span></p>
                            <p><span class="text-sm text-gray-600">Modifié le : </span><span class="text-sm text-gray-900">{{ $etudiant->updated_at->format('d/m/Y à H:i') }}</span></p>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i> Statistiques
                        </h3>
                        <div class="space-y-2">
                            <p><span class="text-sm text-gray-600">Compte créé depuis : </span><span class="text-sm text-gray-900">{{ $etudiant->created_at->diffForHumans() }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('etudiants.edit', $etudiant) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </a>
                    <form method="POST" action="{{ route('etudiants.destroy', $etudiant) }}" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center space-x-2">
                            <i class="fas fa-trash"></i>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
