@extends('layouts.admin')

@section('title', 'Créer une Classe')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- en tete avec titre et bouton retour -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Créer une Nouvelle Classe</h1>
            <p class="text-gray-600 mt-1">Ajoutez une nouvelle classe à votre établissement</p>
        </div>
        <a href="{{ route('classes.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <!-- formulaire de creation -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('classes.store') }}" method="POST" id="classeForm">
            @csrf

            <!-- champ pour le nom de la classe -->
            <div class="mb-6">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la classe <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nom"
                       id="nom"
                       value="{{ old('nom') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror"
                       required
                       placeholder="Ex: B2Dev, B3Crea, B2Comm.">
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Le nom de la classe doit etre unique.</p>
            </div>

            <!-- note d'information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h2 class="text-sm font-medium text-blue-900 mb-1">Information</h2>
                        <p class="text-sm text-blue-700">Apres avoir cree la classe, vous pourrez :</p>
                        <ul class="text-sm text-blue-700 mt-2 list-disc list-inside">
                            <li>L'associer a des annees academiques</li>
                            <li>Inscrire des etudiants</li>
                            <li>Programmer des seances de cours</li>
                            <li>Assigner des coordinateurs</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- boutons en bas du formulaire -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('classes.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i>Créer la Classe
                </button>
            </div>
        </form>
    </div>
</div>

<!-- script pour valider le champ nom en temps reel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // mettre le curseur automatiquement dans le champ nom
    document.getElementById('nom').focus();

    const nomInput = document.getElementById('nom');
    const form = document.getElementById('classeForm');

    // on verifie si le nom est assez long a chaque frappe
    nomInput.addEventListener('input', function() {
        if (this.value.trim().length < 2) {
            this.classList.add('border-red-500');
            this.classList.remove('border-green-500');
        } else {
            this.classList.remove('border-red-500');
            this.classList.add('border-green-500');
        }
    });

    // avant d'envoyer le formulaire, on s'assure que le nom est correct
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
