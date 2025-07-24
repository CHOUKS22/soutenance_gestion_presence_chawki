@extends('layouts.coordinateur')

@section('title', 'Inscription Groupée')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('etudiants-classes.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Inscription Groupée d'Étudiants</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('etudiants-classes.enregistrer-plusieurs') }}">
            @csrf

            <!-- Sélection de la classe -->
            <div class="mb-6">
                <label for="annee_classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Classe / Année académique <span class="text-red-500">*</span>
                </label>
                <select name="annee_classe_id" id="annee_classe_id" required
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('annee_classe_id') border-red-400 @enderror">
                    <option value="">Choisir une classe</option>
                    @foreach($anneeClasses as $anneeClasse)
                        <option value="{{ $anneeClasse->id }}" {{ old('annee_classe_id') == $anneeClasse->id ? 'selected' : '' }}>
                            {{ $anneeClasse->classe->nom }} - {{ $anneeClasse->anneeAcademique->libelle }}
                        </option>
                    @endforeach
                </select>
                @error('annee_classe_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sélection des étudiants -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Étudiants à inscrire <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-2">
                        <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">
                            Tout sélectionner
                        </button>
                        <button type="button" onclick="deselectAll()" class="text-sm text-red-600 hover:text-red-800">
                            Tout désélectionner
                        </button>
                    </div>
                </div>

                <div class="border rounded-lg max-h-96 overflow-y-auto p-4">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($etudiants as $etudiant)
                        <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="etudiant_ids[]" value="{{ $etudiant->id }}"
                                   class="mr-3 etudiant-checkbox"
                                   {{ in_array($etudiant->id, old('etudiant_ids', [])) ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $etudiant->user->email }}
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @error('etudiant_ids')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="mt-2 text-sm text-gray-600">
                    <span id="selected-count">0</span> étudiant(s) sélectionné(s)
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('etudiants-classes.index') }}"
                   class="px-4 py-2 border text-gray-700 rounded hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-users mr-1"></i>
                    Inscrire les étudiants
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function selectAll() {
        const checkboxes = document.querySelectorAll('.etudiant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    }

    function deselectAll() {
        const checkboxes = document.querySelectorAll('.etudiant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.etudiant-checkbox:checked');
        document.getElementById('selected-count').textContent = checkboxes.length;
    }

    // Mettre à jour le compteur au changement
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.etudiant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        updateSelectedCount();
    });
</script>
@endsection
