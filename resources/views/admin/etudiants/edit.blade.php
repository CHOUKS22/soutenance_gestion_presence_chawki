@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Titre -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Modifier les infos de l'étudiant</h1>
            <a href="{{ route('etudiants.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>

        <!-- Messages de retour -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 border border-green-400 rounded px-4 py-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 border border-red-400 rounded px-4 py-3 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Bloc infos utilisateur -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-user mr-2"></i> Infos de l'utilisateur
            </h2>

            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="text-gray-600">Nom</label>
                    <p class="text-gray-900">{{ $etudiant->user->nom }}</p>
                </div>
                <div>
                    <label class="text-gray-600">Prénom</label>
                    <p class="text-gray-900">{{ $etudiant->user->prenom }}</p>
                </div>
                <div>
                    <label class="text-gray-600">Email</label>
                    <p class="text-gray-900">{{ $etudiant->user->email }}</p>
                </div>
                <div>
                    <label class="text-gray-600">Photo</label>
                    <div class="flex items-center space-x-2">
                        @if ($etudiant->user->photo)
                            <img src="{{ asset('storage/' . $etudiant->user->photo) }}" class="h-10 w-10 rounded-full object-cover border" alt="Photo">
                            <span class="text-gray-700">Photo dispo</span>
                        @else
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center border">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <span class="text-gray-700">Aucune photo</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <form method="POST" action="{{ route('etudiants.update', $etudiant) }}" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-edit mr-2"></i> Modifier les champs personnels
            </h2>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Date de naissance -->
                <div>
                    <label for="date_naissance" class="block text-sm text-gray-700 mb-1">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance"
                           value="{{ old('date_naissance', $etudiant->date_naissance ? $etudiant->date_naissance->format('Y-m-d') : '') }}"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-500"
                           required>
                    @error('date_naissance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lieu de naissance -->
                <div>
                    <label for="lieu_naissance" class="block text-sm text-gray-700 mb-1">Lieu de naissance</label>
                    <input type="text" id="lieu_naissance" name="lieu_naissance"
                           value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-500"
                           required>
                    @error('lieu_naissance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="md:col-span-2">
                    <label for="telephone" class="block text-sm text-gray-700 mb-1">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                           value="{{ old('telephone', $etudiant->telephone) }}"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-500"
                           required>
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <a href="{{ route('etudiants.show', $etudiant) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Sauvegarder</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
