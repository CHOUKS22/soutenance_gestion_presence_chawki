@extends('layouts.admin')

@section('title', 'Modifier la Matière')
@section('subtitle', 'Mettez à jour les informations de la matière')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6">
        <!-- Bouton retour -->
        <div class="flex items-center space-x-4 mb-6">
            <a href="{{ route('matieres.show', $matiere->id) }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Modifier la Matière</h1>
        </div>

        <!-- Formulaire de modification -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="POST" action="{{ route('matieres.update', $matiere->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom de la Matière <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom', $matiere->nom) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror">
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
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $matiere->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('matieres.show', $matiere->id) }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Mettre à Jour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validation JS simple
    document.querySelector('form').addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const description = document.getElementById('description').value.trim();

        if (nom.length < 2) {
            e.preventDefault();
            alert('Le nom de la matière doit contenir au moins 2 caractères.');
        }

        if (description.length < 10) {
            e.preventDefault();
            alert('La description doit contenir au moins 10 caractères.');
        }
    });
</script>
@endsection
