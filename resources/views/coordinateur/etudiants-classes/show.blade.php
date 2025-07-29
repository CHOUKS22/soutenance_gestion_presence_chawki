@extends('layouts.coordinateur')

@section('title', 'Détails de l\'Inscription')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('etudiants-classes.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Informations de l'etudiant -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Informations de l'étudiant
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nom complet</label>
                        <p class="text-gray-900">{{ $inscription->etudiant->user->nom }} {{ $inscription->etudiant->user->prenom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-900">{{ $inscription->etudiant->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Téléphone</label>
                        <p class="text-gray-900">{{ $inscription->etudiant->user->telephone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informations de la classe -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-school mr-2 text-green-500"></i>
                    Informations de la classe
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Classe</label>
                        <p class="text-gray-900">{{ $inscription->anneeClasse->classe->nom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Année académique</label>
                        <p class="text-gray-900">{{ $inscription->anneeClasse->anneeAcademique->libelle }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Coordinateur</label>
                        <p class="text-gray-900">
                            @if($inscription->anneeClasse->coordinateur)
                                {{ $inscription->anneeClasse->coordinateur->user->nom }} {{ $inscription->anneeClasse->coordinateur->user->prenom }}
                            @else
                                Non assigné
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de l'inscription -->
        <div class="mt-8 pt-6 border-t">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar mr-2 text-purple-500"></i>
                Informations de l'inscription
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Date d'inscription</label>
                    <p class="text-gray-900">{{ $inscription->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Dernière modification</label>
                    <p class="text-gray-900">{{ $inscription->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
            <a href="{{ route('etudiants-classes.edit', $inscription->id) }}"
               class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                <i class="fas fa-edit mr-1"></i>
                Modifier
            </a>
            <form action="{{ route('etudiants-classes.destroy', $inscription->id) }}"
                  method="POST" class="inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <i class="fas fa-trash mr-1"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
