@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('gestion-types-seances.index') }}"
                   class="text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Nouveau Type de Séance</h1>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="POST" action="{{ route('gestion-types-seances.store') }}" class="space-y-6">
                @csrf

                <!-- Section: Informations principales -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        Informations du Type de Séance
                    </h2>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nom du type -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du Type <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" required
                                   value="{{ old('nom') }}"
                                   placeholder="Ex: Cours magistral, Travaux dirigés, Travaux pratiques..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror">
                            @error('nom')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                      placeholder="Décrivez le type de séance, ses objectifs et ses caractéristiques..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">
                                Une description claire aide à distinguer les différents types de séances
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section: Exemples et conseils -->
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                        Exemples de Types de Séances
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-2">Cours Magistral</h4>
                            <p class="text-sm text-blue-700">Enseignement théorique dispensé par le professeur à l'ensemble de la classe.</p>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg">
                            <h4 class="font-semibold text-green-900 mb-2">Travaux Dirigés (TD)</h4>
                            <p class="text-sm text-green-700">Séances d'exercices et d'applications pratiques en petits groupes.</p>
                        </div>

                        <div class="p-4 bg-purple-50 rounded-lg">
                            <h4 class="font-semibold text-purple-900 mb-2">Travaux Pratiques (TP)</h4>
                            <p class="text-sm text-purple-700">Séances de manipulation et d'expérimentation en laboratoire.</p>
                        </div>

                        <div class="p-4 bg-orange-50 rounded-lg">
                            <h4 class="font-semibold text-orange-900 mb-2">Contrôle Continu</h4>
                            <p class="text-sm text-orange-700">Séances d'évaluation et de contrôle des connaissances.</p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('gestion-types-seances.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Créer le Type</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const description = document.getElementById('description').value.trim();

        if (!nom || !description) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }

        if (nom.length < 3) {
            e.preventDefault();
            alert('Le nom du type doit contenir au moins 3 caractères.');
            return false;
        }

        if (description.length < 10) {
            e.preventDefault();
            alert('La description doit contenir au moins 10 caractères.');
            return false;
        }
    });

</script>
@endsection
