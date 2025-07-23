@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Titre -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier le Coordinateur</h1>
            <a href="{{ route('coordinateurs.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i><span>Retour</span>
            </a>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('coordinateurs.update', $coordinateur) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de coordinateur
                        </label>
                        <select id="role" name="role"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">Sélectionner un rôle</option>
                            <option value="coordinateur pédagogique" {{ old('role', $coordinateur->role) === 'coordinateur pédagogique' ? 'selected' : '' }}>
                                Coordinateur Pédagogique
                            </option>
                            <option value="coordinateur de filière" {{ old('role', $coordinateur->role) === 'coordinateur de filière' ? 'selected' : '' }}>
                                Coordinateur de Filière
                            </option>
                        </select>
                        @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('coordinateurs.index') }}"
                           class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 flex items-center space-x-2">
                            <i class="fas fa-times"></i><span>Annuler</span>
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 flex items-center space-x-2">
                            <i class="fas fa-save"></i><span>Mettre à jour</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
