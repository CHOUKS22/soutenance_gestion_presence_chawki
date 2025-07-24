@extends('layouts.admin')

@section('title', 'Créer un Statut de Présence')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Créer un Statut de Présence</h1>
            <p class="text-gray-600 mt-1">Définir un nouveau statut pour la gestion des présences</p>
        </div>
        <a href="{{ route('statuts-presences.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('statuts-presences.store') }}" method="POST" id="statutPresenceForm">
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
                           placeholder="Ex: Présent, Absent, Retard..."
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
                <a href="{{ route('statuts-presences.index') }}"
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

    const commonStatuts = [
        'Présent',
        'Absent',
        'Retard',
        'Absent justifié',
        'Absent non justifié',
        'Retard justifié',
        'Exclusion temporaire'
    ];

    libelleInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        const suggestions = commonStatuts.filter(statut =>
            statut.toLowerCase().includes(value) && value.length > 0
        );

        if (suggestions.length > 0) {
            suggestionsDiv.innerHTML = '<div class="text-sm text-gray-600 mb-2">Suggestions :</div>' +
                suggestions.map(suggestion =>
                    `<span class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded mr-2 mb-2 cursor-pointer sugitem">
                        ${suggestion}
                    </span>`
                ).join('');

            // Ajouter les événements click sur les suggestions
            document.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    libelleInput.value = this.textContent.trim();
                    suggestionsDiv.innerHTML = '';
                });
            });
        } else {
            suggestionsDiv.innerHTML = '';
        }
    });

    const form = document.getElementById('statutPresenceForm');
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
