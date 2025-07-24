@extends('layouts.admin')

@section('title', 'Créer une Association Année-Classe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Créer une Association Année-Classe</h1>
            <p class="text-gray-600 mt-1">Associer une classe à une année avec un coordinateur</p>
        </div>
        <a href="{{ route('annees-classes.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('annees-classes.store') }}" method="POST" id="anneeClasseForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- annee academique --}}
                <div>
                    <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Année Académique <span class="text-red-500">*</span>
                    </label>
                    <select name="annee_academique_id" id="annee_academique_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('annee_academique_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner une année académique</option>
                        @foreach($anneesAcademiques as $annee)
                            <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle }}
                            </option>
                        @endforeach
                    </select>
                    @error('annee_academique_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- classe --}}
                <div>
                    <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Classe <span class="text-red-500">*</span>
                    </label>
                    <select name="classe_id" id="classe_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('classe_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- coordinateur --}}
                <div class="md:col-span-2">
                    <label for="coordinateur_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Coordinateur <span class="text-red-500">*</span>
                    </label>
                    <select name="coordinateur_id" id="coordinateur_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('coordinateur_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner un coordinateur</option>
                        @foreach($coordinateurs as $coordinateur)
                            <option value="{{ $coordinateur->id }}" {{ old('coordinateur_id') == $coordinateur->id ? 'selected' : '' }}>
                                {{ $coordinateur->user->nom }} {{ $coordinateur->user->prenom }}
                                ({{ $coordinateur->user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('coordinateur_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('annees-classes.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Créer l'Association
                </button>
            </div>
        </form>
    </div>
</div>

{{-- verifie si tous les champs sont remplis avant d'envoyer --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('anneeClasseForm');

    form.addEventListener('submit', function(e) {
        const annee = document.getElementById('annee_academique_id').value;
        const classe = document.getElementById('classe_id').value;
        const coord = document.getElementById('coordinateur_id').value;

        if (!annee || !classe || !coord) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
});
</script>
@endsection
