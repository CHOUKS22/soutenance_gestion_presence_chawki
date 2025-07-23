@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Titre principal de la page -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajouter les Informations d'un Etudiant</h1>

        <!-- Affiche un message d'erreur s'il y en a un -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Verifie s'il y a des utilisateurs disponibles -->
        @if ($usersEtudiants->count() > 0)
            <!-- Debut du formulaire -->
            <form method="POST" action="{{ route('etudiants.store') }}">
                @csrf

                <!-- Grille responsive pour organiser les champs -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Selection d'un utilisateur existant -->
                    <div class="md:col-span-2">
                        <label for="user_id" class="text-sm font-medium text-gray-700 block mb-1">Selectionner l'etudiant</label>
                        <select name="user_id" id="user_id" required class="w-full border px-3 py-2 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Choisir un etudiant --</option>
                            @foreach($usersEtudiants as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nom }} {{ $user->prenom }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label for="date_naissance" class="text-sm font-medium text-gray-700 block mb-1">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required class="w-full border px-3 py-2 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('date_naissance') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Lieu de naissance -->
                    <div>
                        <label for="lieu_naissance" class="text-sm font-medium text-gray-700 block mb-1">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" required class="w-full border px-3 py-2 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('lieu_naissance') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Telephone -->
                    <div class="md:col-span-2">
                        <label for="telephone" class="text-sm font-medium text-gray-700 block mb-1">Telephone</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" required class="w-full border px-3 py-2 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @error('telephone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Boutons du formulaire -->
                <div class="flex justify-end mt-6 gap-3">
                    <a href="{{ route('etudiants.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Ajouter
                    </button>
                </div>
            </form>
        @else
            <!-- Message si aucun utilisateur n'est disponible -->
            <div class="text-center py-8">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl"></i>
                </div>
                <p class="text-gray-600 mb-2">Aucun utilisateur avec le role "Etudiant" disponible</p>
                <p class="text-sm text-gray-500">Vous devez d'abord creer des utilisateurs avec le role "Etudiant" dans la section Utilisateurs</p>
                <a href="{{ route('etudiants.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Gerer les utilisateurs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
