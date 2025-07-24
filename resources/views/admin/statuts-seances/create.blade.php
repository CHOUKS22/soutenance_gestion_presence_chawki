@extends('layouts.admin')

@section('title', 'Créer un Statut de Séance')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Créer un Statut de Séance</h1>
            <p class="text-gray-600 mt-1">Définir un nouveau statut pour la gestion des séances</p>
        </div>
        <a href="{{ route('statuts-seances.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('statuts-seances.store') }}" method="POST" id="statutSeanceForm">
            @csrf
            <div class="space-y-6">
                <!-- Libellé -->
                <div>
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        Libellé <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="libelle" id="libelle"
                           value="{{ old('libelle') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Programmée, En cours, Terminée, Annulée..."
                           required>
                    @error('libelle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div id="suggestions" class="mt-2"></div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Description détaillée du statut (optionnel)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('statuts-seances.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Créer le Statut
                </button>
            </div>
        </form>
    </div>
</div>

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const libelleInput = document.getElementById('libelle');
    const suggestionsDiv = document.getElementById('suggestions');


    const form = document.getElementById('statutSeanceForm');
    form.addEventListener('submit', function(e) {
        const libelle = libelleInput.value.trim();
        if (!libelle) {
            e.preventDefault();
            alert('Le libellé est obligatoire.');
            return false;
        }
    });
});
</script> --}}
@endsection
