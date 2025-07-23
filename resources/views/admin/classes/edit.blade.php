@extends('layouts.admin')

@section('title', 'Modifier la Classe')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- titre et actions en haut -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier la Classe</h1>
            <p class="text-gray-600 mt-1">Modifiez les informations de la classe "{{ $classe->nom }}"</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('classes.show', $classe) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-eye mr-2"></i>Voir Details
            </a>
            <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- formulaire -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('classes.update', $classe) }}" method="POST" id="classeForm">
            @csrf
            @method('PUT')

            <!-- champ nom de la classe -->
            <div class="mb-6">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la classe <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $classe->nom) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror" required placeholder="Ex: Licence 1, Master 2, etc.">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- dates systeme -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <p><strong>Classe creee le :</strong> {{ $classe->created_at->format('d/m/Y a H:i') }}</p>
                    <p><strong>Derniere modification :</strong> {{ $classe->updated_at->format('d/m/Y a H:i') }}</p>
                </div>
            </div>

            <!-- boutons d'action -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Modifier la Classe
                </button>
            </div>
        </form>
    </div>
</div>

<!-- script js pour valider le champ nom -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ce code met le curseur directement dans le champ nom quand la page s'affiche
    document.getElementById('nom').focus();

    // On verifie en direct que l'utilisateur a tape au moins 2 lettres
    const nomInput = document.getElementById('nom');
    const form = document.getElementById('classeForm');

    nomInput.addEventListener('input', function() {
        if (this.value.trim().length < 2) {
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        }
    });

    // Juste avant d'envoyer le formulaire, on controle encore une fois la valeur
    form.addEventListener('submit', function(e) {
        const nom = nomInput.value.trim();

        if (nom.length < 2) {
            e.preventDefault();
            alert('Le nom de la classe doit contenir au moins 2 caracteres.');
            nomInput.focus();
            return false;
        }
    });
});
</script>
@endsection
