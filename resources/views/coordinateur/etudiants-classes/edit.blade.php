@extends('layouts.coordinateur')

@section('title', 'Modifier l\'Inscription')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center mb-6">
        <a href="{{ route('etudiants-classes.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('etudiants-classes.update', $inscription->id) }}">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Classe -->
                <div>
                    <label for="annee_classe_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Classe / Année académique <span class="text-red-500">*</span>
                    </label>
                    <select name="annee_classe_id" id="annee_classe_id" required
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('annee_classe_id') border-red-400 @enderror">
                        <option value="">Choisir une classe</option>
                        @foreach($anneeClasses as $anneeClasse)
                            <option value="{{ $anneeClasse->id }}"
                                {{ (old('annee_classe_id', $inscription->annee_classe_id) == $anneeClasse->id) ? 'selected' : '' }}>
                                {{ $anneeClasse->classe->nom }} - {{ $anneeClasse->anneeAcademique->libelle }}
                            </option>
                        @endforeach
                    </select>
                    @error('annee_classe_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Étudiant -->
                <div>
                    <label for="etudiant_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Étudiant <span class="text-red-500">*</span>
                    </label>
                    <select name="etudiant_id" id="etudiant_id" required
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 @error('etudiant_id') border-red-400 @enderror">
                        <option value="">Choisir un étudiant</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}"
                                {{ (old('etudiant_id', $inscription->etudiant_id) == $etudiant->id) ? 'selected' : '' }}>
                                {{ $etudiant->user->nom }} {{ $etudiant->user->prenom }} ({{ $etudiant->user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('etudiant_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informations actuelles -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Informations actuelles:</h4>
                <p class="text-sm text-gray-600">
                    Étudiant: {{ $inscription->etudiant->user->nom }} {{ $inscription->etudiant->user->prenom }}<br>
                    Classe: {{ $inscription->anneeClasse->classe->nom }} - {{ $inscription->anneeClasse->anneeAcademique->libelle }}<br>
                    Inscrit le: {{ $inscription->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('etudiants-classes.index') }}"
                   class="px-4 py-2 border text-gray-700 rounded hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
