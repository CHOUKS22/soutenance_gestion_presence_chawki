@extends('layouts.admin')

@section('title', 'Creer une Classe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tete avec titre et bouton retour -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Creer une Nouvelle Classe</h1>
            <p class="text-gray-600 mt-1">Ajoutez une nouvelle classe a votre etablissement</p>
        </div>
        <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <!-- Bloc formulaire -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('classes.store') }}" method="POST" id="classeForm">
            @csrf

            <!-- Champ du nom de la classe -->
            <div class="mb-6">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la classe <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror" required placeholder="Ex: B2Dev, B3Crea, B2Comm.">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Le nom de la classe doit etre unique.</p>
            </div>

            <!-- Informations utiles apres creation -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-900 mb-1">Information</p>
                        <p class="text-sm text-blue-700">
                            Apres avoir cree la classe, vous pourrez :
                        </p>
                        <ul class="text-sm text-blue-700 mt-2 list-disc list-inside">
                            <li>L'associer a des annees academiques</li>
                            <li>Inscrire des etudiants</li>
                            <li>Programmer des seances de cours</li>
                            <li>Assigner des coordinateurs</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Creer la Classe
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script pour valider en direct et guider l'utilisateur -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // on met le curseur dans le champ automatiquement
    document.getElementById('nom').focus();

    const nomInput = document.getElementById('nom');
    const form = document.getElementById('classeForm');

    // quand on tape on verifie en temps reel si le champ est correct
    nomInput.addEventListener('input', function() {
        if (this.value.trim().length < 2) {
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        }
    });

    // avant de valider on re-verifie une derniere fois
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
