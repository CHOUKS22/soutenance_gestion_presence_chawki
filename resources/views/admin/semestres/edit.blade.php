@extends('layouts.admin')

@section('title', 'Modifier le Semestre')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- en-tete --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier le Semestre</h1>
            <p class="text-gray-600 mt-1">{{ $semestre->libelle }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('semestres.show', $semestre->id) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir
            </a>
            <a href="{{ route('semestres.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    {{-- formulaire de modification --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('semestres.update', $semestre->id) }}" method="POST" id="semestreForm">
            @csrf
            @method('PUT')

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
                            <option value="{{ $annee->id }}"
                                {{ (old('annee_academique_id', $semestre->annee_academique_id) == $annee->id) ? 'selected' : '' }}>
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
                           value="{{ old('libelle', $semestre->libelle) }}"
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
                           value="{{ old('date_debut', $semestre->date_debut ? \Carbon\Carbon::parse($semestre->date_debut)->format('Y-m-d') : '') }}"
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
                           value="{{ old('date_fin', $semestre->date_fin ? \Carbon\Carbon::parse($semestre->date_fin)->format('Y-m-d') : '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('date_fin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- dates de creation / modif --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h2 class="text-sm font-medium text-gray-800 mb-2">Informations</h2>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Cree le:</span> {{ $semestre->created_at->format('d/m/Y à H:i') }}</p>
                    <p><span class="font-medium">Modifie le:</span> {{ $semestre->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>

            {{-- actions --}}
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('semestres.show', $semestre->id) }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Mettre a jour
                </button>
            </div>
        </form>
    </div>

    {{-- zone de suppression --}}
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-red-800">Zone de danger</h3>
                <p class="text-red-600 text-sm mt-1">
                    Supprimer ce semestre est irreversible. Toutes les donnees liees seront effacees.
                </p>
            </div>
            <button onclick="confirmDelete()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-trash mr-2"></i>Supprimer
            </button>
        </div>
    </div>
</div>

{{-- modal de suppression --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-lg font-medium text-gray-900">Confirmer la suppression</h4>
                </div>
            </div>

            <p class="text-gray-500 mb-6">
                Vous etes sur de vouloir supprimer ce semestre ? Cette action est definitive.
            </p>

            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <form action="{{ route('semestres.destroy', $semestre->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');

    dateDebutInput.addEventListener('change', function() {
        if (dateFinInput.value && new Date(this.value) >= new Date(dateFinInput.value)) {
            alert('La date de debut doit etre avant la date de fin.');
            this.focus();
        }
    });

    dateFinInput.addEventListener('change', function() {
        if (dateDebutInput.value && new Date(this.value) <= new Date(dateDebutInput.value)) {
            alert('La date de fin doit etre apres la date de debut.');
            this.focus();
        }
    });
});

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
