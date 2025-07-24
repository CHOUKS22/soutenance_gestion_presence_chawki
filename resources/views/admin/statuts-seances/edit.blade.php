@extends('layouts.admin')

@section('title', 'Modifier le Statut de Séance')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier le Statut de Séance</h1>
            <p class="text-gray-600 mt-1">{{ $statutSeance->nom }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('statuts-seances.show', $statutSeance->id) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir
            </a>
            <a href="{{ route('statuts-seances.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('statuts-seances.update', $statutSeance->id) }}" method="POST" id="statutForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Libellé -->
                <div class="md:col-span-2">
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        Libellé du statut <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="libelle"
                           id="libelle"
                           value="{{ old('libelle', $statutSeance->libelle) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Ex: Planifiée, En cours, Terminée, Annulée">
                    @error('libelle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du statut de séance">{{ old('description', $statutSeance->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <!-- Informations de modification -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-800 mb-2">Informations de modification</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Créé le:</span> {{ $statutSeance->created_at ? $statutSeance->created_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                    <p><span class="font-medium">Dernière modification:</span> {{ $statutSeance->updated_at ? $statutSeance->updated_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('statuts-seances.show', $statutSeance->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                </div>
            </div>

            <p class="text-gray-500 mb-6">
                Êtes-vous sûr de vouloir supprimer ce statut de séance ?
                @if($statutSeance->seances && $statutSeance->seances->count() > 0)
                    Cette action affectera {{ $statutSeance->seances->count() }} séance(s) enregistrée(s).
                @endif
                Cette action est irréversible.
            </p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <form action="{{ route('statuts-seances.destroy', $statutSeance->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
