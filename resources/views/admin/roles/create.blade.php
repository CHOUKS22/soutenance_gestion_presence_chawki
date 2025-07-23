@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Haut de page avec titre et retour -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <!-- Lien pour revenir à la liste -->
                <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Nouveau Rôle</h1>
            </div>
        </div>

        <!-- Formulaire de création de rôle -->
        <div class="bg-white shadow-md rounded-xl p-8">
            <form method="POST" action="{{ route('roles.store') }}" class="space-y-6">
                @csrf

                <!-- Partie 1 : Informations générales -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        Informations du Rôle
                    </h2>

                    <!-- Champ Nom du rôle -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du Rôle <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" required
                               value="{{ old('nom') }}"
                               placeholder="Ex: Administrateur, Professeur..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror">
                        @error('nom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Champ Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  placeholder="Décrivez le rôle et ses fonctions"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">
                            Une bonne description aide à mieux comprendre le rôle
                        </p>
                    </div>
                </div>

                <!-- Partie 2 : Exemples de rôles -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                        Exemples de Rôles
                    </h2>

                    <!-- Quatre exemples disposés en grille -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-red-50 rounded-lg">
                            <h4 class="font-semibold text-red-900 mb-1">Administrateur</h4>
                            <p class="text-sm text-red-700">Accès complet au système, gestion globale.</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-1">Professeur</h4>
                            <p class="text-sm text-blue-700">Gestion des séances, suivi des présences.</p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <h4 class="font-semibold text-green-900 mb-1">Coordinateur</h4>
                            <p class="text-sm text-green-700">Coordination et gestion des classes.</p>
                        </div>
                        <div class="p-4 bg-purple-50 rounded-lg">
                            <h4 class="font-semibold text-purple-900 mb-1">Étudiant</h4>
                            <p class="text-sm text-purple-700">Accès personnel aux séances et suivi.</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('roles.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Créer le Rôle</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Lorsqu'on soumet le formulaire, on verifie si les champs sont valides
    document.querySelector('form').addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const description = document.getElementById('description').value.trim();

        // Si l'un des champs est vide ou trop court, on bloque
        if (!nom || !description || nom.length < 3 || description.length < 10) {
            e.preventDefault();
            alert("Merci de remplir tous les champs correctement avant d'envoyer.");
            return false;
        }
    });

    // Suggestions automatiques si l'utilisateur tape un rôle courant
    // document.getElementById('nom').addEventListener('input', function() {
    //     const nom = this.value.toLowerCase();
    //     const description = document.getElementById('description');

    //     if (!description.value) {
    //         if (nom.includes('admin')) {
    //             description.value = 'Accès complet au système avec gestion des utilisateurs et paramètres.';
    //         } else if (nom.includes('prof')) {
    //             description.value = 'Gère les cours et suit les étudiants.';
    //         } else if (nom.includes('coord')) {
    //             description.value = 'Coordination pédagogique et gestion de classe.';
    //         } else if (nom.includes('etud')) {
    //             description.value = 'Accès limité pour suivre son propre parcours.';
    //         }
    //     }
    // });
</script>
@endsection
