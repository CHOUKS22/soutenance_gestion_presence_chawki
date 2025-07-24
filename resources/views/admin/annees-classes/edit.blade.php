@extends('layouts.admin')

@section('title', 'Modifier l\'Association Année-Classe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'Association Année-Classe</h1>
            <p class="text-gray-600 mt-1">{{ $anneeClasse->anneeAcademique->libelle }} - {{ $anneeClasse->classe->nom }}</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('annees-classes.show', $anneeClasse) }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir Détails
            </a>
            <a href="{{ route('annees-classes.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('annees-classes.update', $anneeClasse) }}" method="POST" id="anneeClasseForm">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- annee academique --}}
                <div>
                    <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Année Académique <span class="text-red-500">*</span>
                    </label>
                    <select name="annee_academique_id" id="annee_academique_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Sélectionner une année académique</option>
                        @foreach($anneesAcademiques as $annee)
                            <option value="{{ $annee->id }}"
                                {{ (old('annee_academique_id') ?? $anneeClasse->annee_academique_id) == $annee->id ? 'selected' : '' }}>
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
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}"
                                {{ (old('classe_id') ?? $anneeClasse->classe_id) == $classe->id ? 'selected' : '' }}>
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
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Sélectionner un coordinateur</option>
                        @foreach($coordinateurs as $coordinateur)
                            <option value="{{ $coordinateur->id }}"
                                {{ (old('coordinateur_id') ?? $anneeClasse->coordinateur_id) == $coordinateur->id ? 'selected' : '' }}>
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

            <div class="mt-6 pt-6 border-t border-gray-200 text-sm text-gray-600">
                <p><strong>Ajout :</strong> {{ $anneeClasse->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Modifié :</strong> {{ $anneeClasse->updated_at->format('d/m/Y à H:i') }}</p>
                @if($anneeClasse->etudiants->count() > 0)
                    <p class="mt-2"><strong>Étudiants :</strong> {{ $anneeClasse->etudiants->count() }}</p>
                @endif
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('annees-classes.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>

{{-- verifie si tout est rempli --}}
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
