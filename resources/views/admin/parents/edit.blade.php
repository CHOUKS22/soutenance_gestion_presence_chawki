@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="p-6 max-w-4xl mx-auto">

        {{-- titre et bouton retour --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier les informations du Parent</h1>
            <a href="{{ route('parents.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>

        {{-- messages flash --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- bloc formulaire --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">

                {{-- infos utilisateur (non modifiables) --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations de l'utilisateur</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nom</label>
                            <p class="text-sm text-gray-900">{{ $parent->user->nom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Prenom</label>
                            <p class="text-sm text-gray-900">{{ $parent->user->prenom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-sm text-gray-900">{{ $parent->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Role</label>
                            <p class="text-sm text-gray-900">{{ $parent->user->role->nom ?? 'Non defini' }}</p>
                        </div>
                    </div>
                </div>

                {{-- infos specifiques au parent --}}
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations specifiques au parent</h3>

                <form method="POST" action="{{ route('parents.update', $parent) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- telephone --}}
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">Telephone</label>
                            <input type="text" name="telephone" id="telephone"
                                   value="{{ old('telephone', $parent->telephone) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('telephone') border-red-300 @enderror"
                                   required>
                            @error('telephone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- type de relation --}}
                        <div>
                            <label for="type_relation" class="block text-sm font-medium text-gray-700">Type de relation</label>
                            <select name="type_relation" id="type_relation"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('type_relation') border-red-300 @enderror"
                                    required>
                                <option value="">Selectionner un type</option>
                                <option value="Pére" {{ old('type_relation', $parent->type_relation) === 'Pére' ? 'selected' : '' }}>Pere</option>
                                <option value="Mére" {{ old('type_relation', $parent->type_relation) === 'Mére' ? 'selected' : '' }}>Mere</option>
                                <option value="garant" {{ old('type_relation', $parent->type_relation) === 'garant' ? 'selected' : '' }}>Garant</option>
                                <option value="Tuteur" {{ old('type_relation', $parent->type_relation) === 'Tuteur' ? 'selected' : '' }}>Tuteur</option>
                                <option value="Autre" {{ old('type_relation', $parent->type_relation) === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type_relation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- actions --}}
                    <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('parents.show', $parent) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Sauvegarder</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
