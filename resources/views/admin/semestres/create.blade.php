@extends('layouts.admin')

@section('title', 'Creer un Semestre')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- en-tete --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Creer un Nouveau Semestre</h1>
            <p class="text-gray-600 mt-1">Ajouter un semestre a une annee academique</p>
        </div>
        <a href="{{ route('semestres.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    {{-- formulaire --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('semestres.store') }}" method="POST" id="semestreForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- annee academique --}}
                <div class="md:col-span-2">
                    <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Annee Academique <span class="text-red-500">*</span>
                    </label>
                    <select name="annee_academique_id" id="annee_academique_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Selectionner une annee academique</option>
                        @foreach($annees_academiques as $annee)
                            <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle }}
                            </option>
                        @endforeach
                    </select>
                    @error('annee_academique_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- libelle --}}
                <div class="md:col-span-2">
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-2">
                        Libelle du semestre <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="libelle"
                           id="libelle"
                           value="{{ old('libelle') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required
                           placeholder="Ex: Semestre 1, Semestre 2">
                    @error('libelle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- date debut --}}
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de debut <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="date_debut"
                           id="date_debut"
                           value="{{ old('date_debut') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('date_debut')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- date fin --}}
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="date_fin"
                           id="date_fin"
                           value="{{ old('date_fin') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('date_fin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- boutons --}}
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('semestres.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Creer le Semestre
                </button>
            </div>
        </form>
    </div>
</div>

{{-- script de validation des dates --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');

    dateDebutInput.addEventListener('change', function() {
        if (dateFinInput.value && new Date(this.value) >= new Date(dateFinInput.value)) {
            alert('La date de debut doit etre inferieure a la date de fin.');
            this.value = '';
        }
    });

    dateFinInput.addEventListener('change', function() {
        if (dateDebutInput.value && new Date(this.value) <= new Date(dateDebutInput.value)) {
            alert('La date de fin doit etre superieure a la date de debut.');
            this.value = '';
        }
    });
});
</script>
@endsection
