@extends('layouts.admin')

@section('title', 'Modifier le Statut de Présence')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier le Statut de Présence</h1>
            <p class="text-gray-600 mt-1">{{ $statutPresence->libelle }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('statuts-presences.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
       <form action="{{ route('statuts-presences.update', $statutPresence) }}" method="POST" id="statutForm">
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
                           value="{{ old('libelle', $statutPresence->libelle) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Ex: Présent, Absent, Retard">
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
                              placeholder="Description détaillée du statut de présence">{{ old('description', $statutPresence->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Aperçu des utilisations -->
            @if($statutPresence->presences && $statutPresence->presences->count() > 0)
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Impact des modifications</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Ce statut est utilisé pour <strong>{{ $statutPresence->presences->count() }} présence(s)</strong>.</p>
                            <p>La modification du libellé affectera l'affichage de toutes ces présences.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Informations -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-800 mb-2">Informations de modification</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Créé le:</span> {{ $statutPresence->created_at ? $statutPresence->created_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                    <p><span class="font-medium">Dernière modification:</span> {{ $statutPresence->updated_at ? $statutPresence->updated_at->format('d/m/Y à H:i') : 'Non disponible' }}</p>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('statuts-presences.show', $statutPresence) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
