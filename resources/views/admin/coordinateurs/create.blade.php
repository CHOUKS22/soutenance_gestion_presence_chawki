@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6 ">
    <h2 class="text-xl font-semibold mb-4">Ajouter les informations d'un Coordinateur</h2>

    @if($usersCoordinateurs->count() > 0)
        <form method="POST" action="{{ route('coordinateurs.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner le coordinateur</label>
                    <select id="user_id" name="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">-- Choisir un coordinateur --</option>
                        @foreach($usersCoordinateurs as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle du coordinateur</label>
                    <select id="role" name="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">-- Choisir le rôle --</option>
                        <option value="coordinateur pédagogique" {{ old('role') == 'coordinateur pédagogique' ? 'selected' : '' }}>Coordinateur Pédagogique</option>
                        <option value="coordinateur de filière" {{ old('role') == 'coordinateur de filière' ? 'selected' : '' }}>Coordinateur de Filière</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6">
                <button type="button" id="cancelAddBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Ajouter les informations
                </button>
            </div>
        </form>
    @else
        <div class="text-center py-8">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
            <p class="text-gray-600 mb-2">Aucun utilisateur avec le rôle "Coordinateur" disponible</p>
            <p class="text-sm text-gray-500">Vous devez d'abord créer des utilisateurs avec le rôle "Coordinateur" dans la section Utilisateurs</p>
            <a href="{{ route('coordinateurs.index') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Gérer les utilisateurs
            </a>
        </div>
    @endif
</div>
@endsection